<?php
session_name("examen-rec-22");
session_start();

require "../const_globales/env.php";
const INACTIVIDAD = 5;
const NOMBRE_BD = "bd_guardias_exam";
const HORAS = [
    "1ºHora",
    "2ºHora",
    "3ºHora",
    "4ºHora",
    "5ºHora",
    "6ºHora",
];

require "./src/fun_cte.php";

$conexion = conectar_BD();

if (isset($_POST["btnSalir"])) {
    session_destroy();

    header("Location: index.php");
    exit;
}

if (isset($_SESSION["id_usuario"])) {
    $usuario = comprobarBaneo($conexion, $_SESSION["id_usuario"], "index.php");
    comprobarInactividad($conexion, "index.php");
} else {
    if (isset($_POST["btnLogin"])) {
        $error_usuario = $_POST["usuario"] == "";
        $error_clave = $_POST["clave"] == "";

        $error_form = $error_usuario || $error_clave;

        $tupla = comprobarUsuario($conexion);

        if (!$error_form) {
            if ($tupla) {
                $_SESSION["id_usuario"] = $tupla["id_usuario"];
                $_SESSION["ultima_accion"] = time();

                mysqli_close($conexion);

                header("Location: index.php");
                exit;
            } else {
                $error_usuario = true;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de guardias</title>
    <style>
        form>label {
            display: inline-block;
            width: 100px;
        }

        .error {
            color: red;
        }

        .enlace {
            background: none;
            border: none;
            text-decoration: underline;
            color: blue;
            cursor: pointer;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th {
            background-color: lightgray;
        }

        table {
            width: 95%;
            margin: 0 auto;
            border-collapse: collapse;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_SESSION["id_usuario"])) {
    ?>
        <h1>Gestión de Guardias</h1>
        <form action="index.php" method="post">
            <p>
                Bienvenido <strong><?= $usuario["usuario"] ?></strong> -
                <button class='enlace' type="submit" name="btnSalir">Salir</button>
            </p>
        </form>
        <h2>Equipos de guardia del IES Mar de Alboran</h2>
        <form action="index.php" method="post">
            <table>
                <tr>
                    <th></th>
                    <th>Lunes</th>
                    <th>Martes</th>
                    <th>Miercoles</th>
                    <th>Jueves</th>
                    <th>Viernes</th>
                </tr>
                <?php
                for ($i = 0; $i < 6; $i++) {
                    if ($i == 3) {
                        echo "<tr><td colspan='7'>Recero</td></tr>";
                    }
                    echo "<tr>";
                    echo "<td>" . HORAS[$i] . "</td>";
                    for ($j = 1; $j < 6; $j++) {
                        echo "<td>";
                        echo "<button class='enlace' type='submit' name='btnEquipo'>Equipo " . ($i * 5) + $j . "</button>";
                        echo "<input type='hidden' name='h_dia' value='" . $j . "'>";
                        echo "<input type='hidden' name='h_hora' value='" . ($i + 1)  . "'>";
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </table>
        </form>
    <?php
    } else {
    ?>
        <h1>Gestión de Guardias</h1>
        <form action="index.php" method="post">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" value="<?php if (isset($_POST["usuario"])) echo $_POST["usuario"] ?>">
            <?php
            if (isset($_POST["btnLogin"]) && $error_usuario) {
                if ($_POST["usuario"] == "") {
                    echo "<span class='error'>* Campo obligatorio.</span>";
                } else {
                    echo "<span class='error'>* Credenciales inválidas.</span>";
                }
            }
            ?>
            <br>
            <label for="clave">Contraseña: </label>
            <input type="password" name="clave" id="clave">
            <?php
            if (isset($_POST["btnLogin"]) && $error_clave) {
                if ($_POST["clave"] == "") {
                    echo "<span class='error'>* Campo obligatorio.</span>";
                }
            }
            ?>
            <p>
                <button type="submit" name="btnLogin">Entrar</button>
            </p>
        </form>
    <?php
    }
    ?>
</body>

</html>