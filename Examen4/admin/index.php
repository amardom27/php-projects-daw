<?php
session_name("Examen4");
session_start();

require "../../const_globales/env.php";
require "../src/func_cte.php";

$conexion = conectarBD();

if (isset($_SESSION["cod_usuario"])) {
    $tupla_usu_log = verificar_sesion($conexion, $_SESSION["cod_usuario"], INACTIVIDAD, "index.php");

    if ($tupla_usu_log["tipo"] == "alumno") {
        $_SESSION["cod_usuario"] = $tupla_usu_log["cod_usu"];
        $_SESSION["ultima_accion"] = time();
        mysqli_close($conexion);

        header("Location: ../index.php");
        exit;
    }

    $alumnos = obtener_alumnos($conexion);

    // TODO Hacer que funcine el boton borrar
    if (isset($_POST["btnBorrar"])) {
        borrar_calificacion($conexion, $_POST["h_usu"], $_POST["h_asig"]);

        $_SESSION["mensaje"] = "Asignatura descalificada con éxito.";

        $_POST["btnVerNotas"] = $_POST["h_usu"];

        header("Location: index.php");
        exit;
    }

    if (isset($_POST["btnVerNotas"]) || isset($_POST["btnBorrar"])) {
        $notas = obtner_notas_2($conexion, $_POST["alumno"]);
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
        <h1>Notas de los alumnos</h1>
        <form action="../index.php" method="post">
            <p>
                Bienvenido <strong><?= $tupla_usu_log["usuario"] ?></strong> -
                <button class='enlace' type="submit" name="btnSalir">Salir</button>
            </p>
        </form>
        <?php
        if (count($alumnos) === 0) {
            echo "<p>En estos momentos no tenemos ningún alumno registrado en la BD.</p>";
        } else {
        ?>
            <form action="index.php" method="post">
                <label for="alumno">Seleccion un alumno: </label>
                <select name="alumno" id="alumno">
                    <?php
                    foreach ($alumnos as $alumno) {
                        if (isset($_POST["alumno"]) && $_POST["alumno"] == $alumno["cod_usu"]) {
                            $alumno_selec = $alumno["nombre"];
                            echo "<option value='" . $alumno["cod_usu"] . "' selected>" . $alumno["nombre"] . "</option>";
                        } else {
                            echo "<option value='" . $alumno["cod_usu"] . "'>" . $alumno["nombre"] . "</option>";
                        }
                    }
                    ?>
                </select>
                <button type="submit" name="btnVerNotas">Ver notas</button>
            </form>
            <?php
            if (isset($notas)) {
            ?>
                <h2>Notas del alumno <?= $alumno_selec ?></h2>
                <table>
                    <tr>
                        <th>Asignatura</th>
                        <th>Nota</th>
                        <th>Acción</th>
                    </tr>
                    <?php
                    $falta_calif = false;
                    foreach ($notas as $nota) {
                        if (isset($nota["cod_usu"])) {
                            echo "<tr>";
                            echo "<td>" . $nota["denominacion"] . "</td>";
                            echo "<td>" . $nota["nota"] . "</td>";
                            echo "<td>";
                            echo "<form action='index.php' method='post'>";
                            echo "<button class='enlace' type='submit' name='btnEditar'>Editar</button>";
                            echo " - ";
                            echo "<button class='enlace' type='submit' name='btnBorrar'>Borrar</button>";
                            echo "<input type='hidden' name='h_usu' value='" . $nota["cod_usu"] . "'";
                            echo "<input type='hidden' name='h_asig' value='" . $nota["cod_asig"] . "'";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        } else {
                            $faltan_calif = true;
                        }
                    }
                    ?>
                </table>
                <?php
                if (isset($_SESSION["mensaje"])) {
                    echo "<p>" . $_SESSION["mensaje"] . "</p>";
                    unset($_SESSION["mensaje"]);
                }
                ?>
                <?php
                if (!$faltan_calif) {
                    echo "A $alumno_selec no quedan asignaturas por calificar.";
                } else {
                ?>
                    <p>
                        <input type="hidden" name="h_asig">
                    <form action="index.php" method="post">
                        <label>A <strong><?= $alumno_selec ?></strong> aún le quedan por calificar: </label>
                        <select name="asignatura" id="asignatura">
                            <?php
                            foreach ($notas as $nota) {
                                if (!isset($nota["cod_usu"])) {
                                    if (isset($_POST["btnCalificar"]) && $_POST["calificar"]) {
                                        $asignatura_selec = $nota["denominacion"];
                                        echo "<option selected value='" . $nota["cod_asig"] . "'>" . $nota["denominacion"] . "</option>";
                                    } else {
                                        echo "<option value='" . $nota["cod_asig"] . "'>" . $nota["denominacion"] . "</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                        <button type="submit" name="btnCalificar">Calificar</button>
                    </form>
                    </p>
            <?php
                }
            }
            ?>
        <?php
        }
        ?>
    </body>

    </html>
<?php
} else {
    session_destroy();

    header("Location: ../index.php");
    exit;
}
?>