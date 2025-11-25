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

    if (isset($_POST["btnBorrar"])) {
        //borrar_calificacion($conexion, $_POST["h_usu"], $_POST["h_asig"]);
        $_SESSION["mensaje"] = "Asignatura descalificada con éxito.";
        mysqli_close($conexion);

        header("Location: index.php");
        exit;
    }

    if (isset($_POST["btnCambiar"])) {
        $error_nota = $_POST["nota"] == "" || !is_numeric($_POST["nota"]) || $_POST["nota"] < 0 || $_POST["nota"] > 10;

        if (!$error_nota) {
            try {
                $consulta = "update notas set nota = '" . $_POST["nota"] . "' where cod_usu = '" . $_POST["h_usu"] . "' and cod_asig = '" . $_POST["h_asig"] . "'";
                mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                session_destroy();
                mysqli_close($conexion);
                die(error_page("Examen 4", "<h1>Error en la consulta a la BD</h1><p>" . $e->getMessage() . "</p>"));
            }
            $_SESSION["mensaje"] = "Nota actulizada con éxito.";
            mysqli_close($conexion);

            header("Location: index.php");
            exit;
        }
    }

    if (isset($_POST["btnVerNotas"]) || isset($_SESSION["alumno_selec"])) {
        $cod_alu = $_POST["alumno"] ?? $_SESSION["alumno_selec"];
        $notas = obtener_notas_2($conexion, $cod_alu);

        $_SESSION["alumno_selec"] = $cod_alu;
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

            .info {
                color: blue;
            }

            td form {
                margin-bottom: 0;
            }

            .error {
                color: red;
            }
        </style>
    </head>

    <body>
        <form action="../index.php" method="post">
            <h1>Notas de los alumnos</h1>
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
                        if (isset($_SESSION["alumno_selec"]) && $_SESSION["alumno_selec"] == $alumno["cod_usu"]) {
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
                    $faltan_calif = false;
                    foreach ($notas as $nota) {
                        if (isset($nota["cod_usu"])) {
                            echo "<tr>";
                            echo "<td>" . $nota["denominacion"] . "</td>";
                            if (isset($_POST["btnEditar"]) && $_POST["h_asig"] == $nota["cod_asig"] || (isset($_POST["btnCambiar"]) && $error_nota)) {
                                $value = $_POST["nota"] ?? $nota["nota"];
                                echo "<td>";
                                echo "<form action='index.php' method='post'>";
                                echo "<input type='text' name='nota' value='" . $value . "' placeholder='Teclee un número entre 0 y 10'>";
                                if (isset($_POST["btnCambiar"]) && $error_nota) {
                                    if ($_POST["nota"] == "") {
                                        echo "<br><span class='error'>* Campo obligatorio.</span>";
                                    } else {
                                        echo "<br><span class='error'>* Número inválido.</span>";
                                    }
                                }
                                echo "</td>";
                                echo "<td>";
                                echo "<button class='enlace' type='submit' name='btnCambiar'>Cambiar</button>";
                                echo " - ";
                                echo "<button class='enlace' type='submit' name='btnVolver'>Atrás</button>";
                                echo "<input type='hidden' name='h_usu' value='" . $nota["cod_usu"] . "'>";
                                echo "<input type='hidden' name='h_asig' value='" . $nota["cod_asig"] . "'>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            } else {
                                echo "<td>" . $nota["nota"] . "</td>";
                                echo "<td>";
                                echo "<form action='index.php' method='post'>";
                                echo "<button class='enlace' type='submit' name='btnEditar'>Editar</button>";
                                echo " - ";
                                echo "<button class='enlace' type='submit' name='btnBorrar'>Borrar</button>";
                                echo "<input type='hidden' name='h_usu' value='" . $nota["cod_usu"] . "'>";
                                echo "<input type='hidden' name='h_asig' value='" . $nota["cod_asig"] . "'>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            // echo "<td>";
                            // echo "<form action='index.php' method='post'>";
                            // echo "<button class='enlace' type='submit' name='btnEditar'>Editar</button>";
                            // echo " - ";
                            // echo "<button class='enlace' type='submit' name='btnBorrar'>Borrar</button>";
                            // echo "<input type='hidden' name='h_usu' value='" . $nota["cod_usu"] . "'>";
                            // echo "<input type='hidden' name='h_asig' value='" . $nota["cod_asig"] . "'>";
                            // echo "</form>";
                            // echo "</td>";
                            // echo "</tr>";
                        } else {
                            $faltan_calif = true;
                        }
                    }
                    ?>
                </table>
                <?php
                if (isset($_SESSION["mensaje"])) {
                    echo "<p class='info'>" . $_SESSION["mensaje"] . "</p>";
                    unset($_SESSION["mensaje"]);
                }
                ?>
                <?php
                if (!$faltan_calif) {
                    echo "<p>A <strong>$alumno_selec</strong> no quedan asignaturas por calificar.</p>";
                } else {
                ?>
                    <p>
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