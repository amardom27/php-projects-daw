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
const DIAS = [
    1 => "Lunes",
    "Martes",
    "Miércoles",
    "Jueves",
    "Viernes"
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

    if (isset($_POST["btnEquipo"]) || isset($_POST["btnProfesor"])) {
        try {
            $consulta = "select id_hor_gua from horario_guardias where usuario = '" . $_SESSION["id_usuario"] . "' and dia = '" . $_POST["h_dia"] . "' and hora = '" . $_POST["h_hora"] . "'";
            $res_equipo = mysqli_query($conexion, $consulta);

            $esta = mysqli_fetch_assoc($res_equipo);
            mysqli_free_result($res_equipo);
        } catch (Exception $e) {
            session_destroy();
            mysqli_close($conexion);
            die(error_page("Gestión de Guardias", "<h1>Error en la consulta a la BD</h1><p>" . $e->getMessage() . "</p>"));
        }

        if ($esta) {
            try {
                $consulta = "select id_usuario, nombre, usuarios.usuario, email from usuarios join horario_guardias on usuarios.id_usuario = horario_guardias.usuario where horario_guardias.dia = '" . $_POST["h_dia"] . "' and horario_guardias.hora = '" . $_POST["h_hora"] . "'";
                $res_guardia = mysqli_query($conexion, $consulta);

                $profesores = mysqli_fetch_all($res_guardia, MYSQLI_ASSOC);
                mysqli_free_result($res_guardia);
            } catch (Exception $e) {
                session_destroy();
                mysqli_close($conexion);
                die(error_page("Gestión de Guardias", "<h1>Error en la consulta a la BD</h1><p>" . $e->getMessage() . "</p>"));
            }
        }
    }
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

        .info {
            color: blue;
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
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        .text-start {
            text-align: start;
            padding-left: 1rem;
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
        <table class="w-95">
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
                    echo "<form action='index.php' method='post'>";
                    echo "<button class='enlace' type='submit' name='btnEquipo' value='" . ($i * 5) + $j . "'>Equipo " . ($i * 5) + $j . "</button>";
                    echo "<input type='hidden' name='h_dia' value='" . $j . "'>";
                    echo "<input type='hidden' name='h_hora' value='" . ($i + 1)  . "'>";
                    echo "</form>";
                    echo "</td>";
                }
                echo "</tr>";
            }
            ?>
        </table>
        <?php if (isset($_POST["btnEquipo"]) || isset($_POST["btnProfesor"])) {
            $hora = $_POST["h_hora"] - 1;
            $num_equipo = $_POST["btnEquipo"] ?? $_POST["btnProfesor"];

            echo "<h2>Equipo de guardia " . $num_equipo . "</h2>";
            echo "<h3>" . DIAS[$_POST["h_dia"]] . " a " . HORAS[$hora] . "</h3>";

            if (!$esta) {
                echo "<p>Atención, usted no es encuentra de guardia el " . DIAS[$_POST["h_dia"]] . " a " . HORAS[$hora] . "</p>";
            } else {
                $id_prof_seleccionado = $_POST["h_prof"] ?? "";

                $idx_prof_seleccionado = "";
                if (isset($_POST["btnProfesor"])) {
                    for ($i = 0; $i < count($profesores); $i++) {
                        if ($profesores[$i]["id_usuario"] == $_POST["h_prof"]) {
                            $idx_prof_seleccionado = $i;
                            break;
                        }
                    }
                }

                echo "<table>";
                echo "<tr>";
                echo "<th>Profesor de Guardia</th>";
                echo "<th>Información del profesor con id: $id_prof_seleccionado</th>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>";
                echo "<form action='index.php' method='post'>";
                echo "<button class='enlace' type='submit' name='btnProfesor' value='" . $num_equipo . "'>" . $profesores[0]["nombre"] . "</button>";
                echo "<input type='hidden' name='h_dia' value='" . $_POST["h_dia"] . "'>";
                echo "<input type='hidden' name='h_hora' value='" . $_POST["h_hora"] . "'>";
                echo "<input type='hidden' name='h_prof' value='" . $profesores[0]["id_usuario"] . "'>";
                echo "</form>";
                echo "</td>";

                echo "<td rowspan='" . count($profesores) . "' class='text-start'>";
                // ! Cuidado con las evaluaciones que hace php en los ifs, "" == false
                if ($idx_prof_seleccionado !== "") {
                    echo "<p><strong>Nombre:</strong> " . $profesores[$idx_prof_seleccionado]["nombre"] . "</p>";
                    echo "<p><strong>Usuario:</strong> " . $profesores[$idx_prof_seleccionado]["usuario"] . "</p>";
                    echo "<p><strong>Clave:</strong></p>";
                    echo "<p><strong>Email:</strong> " . $profesores[$idx_prof_seleccionado]["email"] . "</p>";
                }
                echo "</td>";
                echo "</tr>";

                for ($i = 1; $i < count($profesores); $i++) {
                    echo "<tr>";
                    echo "<td>";
                    echo "<form action='index.php' method='post'>";
                    echo "<button class='enlace' type='submit' name='btnProfesor' value='" . $num_equipo . "'>" . $profesores[$i]["nombre"] . "</button>";
                    echo "<input type='hidden' name='h_dia' value='" . $_POST["h_dia"] . "'>";
                    echo "<input type='hidden' name='h_hora' value='" . $_POST["h_hora"] . "'>";
                    echo "<input type='hidden' name='h_prof' value='" . $profesores[$i]["id_usuario"] . "'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }

                echo "</table>";
            }
        }
        ?>
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
            <?php
            if (isset($_SESSION["seguridad"])) {
                echo "<p class='info'>" . $_SESSION["seguridad"] . "</p>";
                unset($_SESSION["seguridad"]);
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