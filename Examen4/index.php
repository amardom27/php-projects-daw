<?php
session_name("Examen4");
session_start();

require "../const_globales/env.php";
require "src/func_cte.php";

$conexion = conectarBD();

if (isset($_POST["btnSalir"])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

if (isset($_SESSION["cod_usuario"])) {
    $tupla_usu_log = verificar_sesion($conexion, $_SESSION["cod_usuario"], INACTIVIDAD, "index.php");

    if ($tupla_usu_log["tipo"] == "tutor") {
        $_SESSION["cod_usuario"] = $tupla_usu_log["cod_usu"];
        $_SESSION["ultima_accion"] = time();
        mysqli_close($conexion);

        header("Location: admin/index.php");
        exit;
    }

    $notas = obtner_notas($conexion, $_SESSION["cod_usuario"]);
} else {
    if (isset($_POST["btnLogin"])) {
        $error_usuario = $_POST["usuario"] == "";
        $error_clave = $_POST["clave"] == "";

        $error_form = $error_usuario || $error_clave;

        if (!$error_form) {
            try {
                $consulta = "select * from usuarios where usuario = '" . $_POST["usuario"] . "' and clave = '" . md5($_POST["clave"]) . "'";
                $res_usuario = mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                session_destroy();
                mysqli_close($conexion);
                die(error_page("Examen 4", "<h1>Error en la consulta a la BD</h1><p>" . $e->getMessage() . "</p>"));
            }

            $tupla = mysqli_fetch_assoc($res_usuario);
            mysqli_free_result($res_usuario);

            if ($tupla) {
                $_SESSION["cod_usuario"] = $tupla["cod_usu"];
                $_SESSION["ultima_accion"] = time();
                mysqli_close($conexion);

                if ($tupla["tipo"] == "tutor") {
                    header("Location: admin/index.php");
                } else {
                    header("Location: index.php");
                }
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
    <title>Examen 4</title>
    <style>
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
            padding: 8px;
        }

        th {
            background-color: lightgray;
        }

        table {
            border-collapse: collapse;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_SESSION["cod_usuario"])) {
        // VISTA LOG NORMAL 
    ?>
        <h1>Notas de los alumnos</h1>
        <form action="index.php" method="post">
            <p>
                Bienvenido <strong><?= $tupla_usu_log["usuario"] ?></strong> -
                <button class='enlace' type="submit" name="btnSalir">Salir</button>
            </p>
        </form>
        <h2>Notas del alumno <?= $tupla_usu_log["nombre"] ?></h2>
        <table>
            <tr>
                <th>Asignatura</th>
                <th>Nota</th>
            </tr>
            <?php
            foreach ($notas as $nota) {
                echo "<tr>";
                echo "<td>" . $nota["denominacion"] . "</td>";
                echo "<td>" . $nota["nota"] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    <?php
    } else {
        // VISTA HOME 
    ?>
        <h1>Notas de los alumnos</h1>
        <form action="index.php" method="post">
            <p>
                <label for="usuario">Usuario: </label>
                <input type="text" name="usuario" id="usuario" value="<?php if (isset($_POST["btnLogin"])) echo $_POST["usuario"] ?>">
                <?php
                if (isset($_POST["btnLogin"]) && $error_form) {
                    if ($_POST["usuario"] == "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    } else {
                        echo "<span class='error'>* Credenciales inválidas.</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="clave">Contraseña: </label>
                <input type="password" name="clave" id="clave">
                <?php
                if (isset($_POST["btnLogin"]) && $error_form) {
                    if ($_POST["clave"] == "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    }
                }
                ?>
            </p>
            <button type="submit" name="btnLogin">Login</button>
        </form>
        <?php
        if (isset($_SESSION["seguridad"])) {
            echo "<p class='mensaje'>" . $_SESSION["seguridad"] . "</p>";
            session_destroy();
        }
        ?>
    <?php
    }
    ?>
</body>

</html>
<?php mysqli_close($conexion); ?>