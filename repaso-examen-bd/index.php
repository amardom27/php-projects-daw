<?php
require("../const_globales/env.php");
const NOMBRE_BD = "bd_horarios_exam";

const DIAS = [
    1 => "Lunes",
    2 => "Martes",
    3 => "Miércoles",
    4 => "Jueves",
    5 => "Viernes"
];

const HORAS = [
    1 => "8:15 - 9:15",
    2 => "9:15 - 10:15",
    3 => "10:15 - 11:15",
    4 => "11:15 - 11:45",
    5 => "11:45 - 12:45",
    6 => "12:45 - 13:45",
    7 => "13:45 - 14:45"
];

function error_page($body) {
    $html = '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Repaso examen 2 PHP</title>
    </head>
    <body>' . $body . '          
    </body>
    </html>';
    return $html;
}

try {
    @$conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE_BD);
    mysqli_set_charset($conexion, "utf8");
} catch (Exception $e) {
    die(error_page("<h1>No se ha podido conectar a la base de datos</h1><p>" . $e->getMessage() . "</p>"));
}


// ? Consulta inicial para obtener los profesores
try {
    $consulta = "select * from usuarios";
    $resultado_profesores = mysqli_query($conexion, $consulta);
} catch (Exception $e) {
    mysqli_close($conexion);
    die(error_page("<h1>No se ha podido realizar la consulta</h1><p>" . $e->getMessage() . "</p>"));
}

// * Consulta para borrar un grupo de una determinada hora
// * Hay que hacerlo antes del horario 
if (isset($_POST["btnQuitar"])) {
    try {
        $consulta = "delete from horario_lectivo where id_horario = '" . $_POST["btnQuitar"] . "'";
        mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page("<h1>No se ha podido realizar la consulta</h1><p>" . $e->getMessage() . "</p>"));
    }

    $mensaje_resultado = "Grupo borrado con éxito";
}



// ? Consulta para mostrar el horario (construir la matriz) 
if (isset($_POST["btnVerHorario"]) || isset($_POST["btnEditar"]) || isset($_POST["btnQuitar"])) {
    if (isset($_POST["btnVerHorario"])) {
        $idProfesorConsulta = $_POST["profesor"];
    } elseif (isset($_POST["btnEditar"])) {
        $idProfesorConsulta = $_POST["btnEditar"];
    } elseif (isset($_POST["btnQuitar"])) {
        $idProfesorConsulta = $_POST["h_profesor"];
    }

    try {
        // SELECT * FROM `horario_lectivo` WHERE `usuario` = 44 ORDER BY `dia` ASC, `hora` ASC; 
        // SELECT dia, hora, nombre FROM `horario_lectivo` JOIN grupos on `horario_lectivo`.`grupo` = `grupos`.`id_grupo` WHERE `usuario` = 44 ORDER BY `dia` ASC, `hora` ASC;
        // SELECT dia, hora, grupo, nombre FROM `horario_lectivo` JOIN grupos on `horario_lectivo`.`grupo` = `grupos`.`id_grupo` WHERE `usuario` = 44 AND `dia` = 4 AND `hora` = 2 ORDER BY `dia` ASC, `hora` ASC; 
        $consulta = "select id_horario, dia, hora, nombre from horario_lectivo join grupos on horario_lectivo.grupo = grupos.id_grupo where usuario = '" . $idProfesorConsulta . "' order by `dia` asc, `hora` asc";
        $resultado_horario = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page("<h1>No se ha podido realizar la consulta</h1><p>" . $e->getMessage() . "</p>"));
    }
}


// ? Consulta para mostrar la segunda tabla 
if (isset($_POST["btnEditar"]) || isset($_POST["btnQuitar"])) {
    $idProfesor = isset($_POST["btnEditar"]) ? $_POST["btnEditar"] : $_POST["h_profesor"];

    try {
        $consulta = "
            select id_horario, dia, hora, nombre, grupo 
            from horario_lectivo 
            join grupos on horario_lectivo.grupo = grupos.id_grupo 
            where usuario = '" . $idProfesor . "'  
            and hora = '" . $_POST["h_hora"] . "'
            and dia = '" . $_POST["h_dia"] . "'
            order by `dia` asc, `hora` asc
        ";
        $resultado_clases = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page("<h1>No se ha podido realizar la consulta</h1><p>" . $e->getMessage() . "</p>"));
    }
}

// ? Consulta para el select de grupos
if (isset($_POST["btnEditar"]) || isset($_POST["btnQuitar"])) {
    try {
        $consulta = "
            select *
            from grupos 
        ";
        $resultado_grupos = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page("<h1>No se ha podido realizar la consulta</h1><p>" . $e->getMessage() . "</p>"));
    }
}

// ! NO OLVDIDAR CERRAR
mysqli_close($conexion);
// TODO Funcionalidad del botón añadir
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repaso examen 2 PHP</title>
    <style>
        table,
        td,
        th {
            border: 1px solid black;
            padding: 6px;
        }

        th {
            background-color: lightgray;
        }

        table {
            border-collapse: collapse;
            text-align: center;
        }

        .w-80 {
            width: 80%;
        }

        .center {
            margin: 0 auto;
        }

        .text-center {
            text-align: center;
        }

        .enlace {
            background: none;
            border: none;
            color: blue;
            text-decoration: underline;
            cursor: pointer
        }

        .error {
            color: red
        }
    </style>
</head>

<body>
    <h1>Examen 2 PHP</h1>
    <h2>Horario de los profesores</h2>
    <?php
    if (mysqli_num_rows($resultado_profesores) > 0) {
    ?>
        <form action="index.php" method="post">
            <label for="profesor">Horario del profesor: </label>
            <select name="profesor" id="profesor">
                <?php
                // Declarar fuera del while para que siempre exista más abajo
                $idProfesorElegido = "";
                $nombreProfesorSeleccionado = "";

                while ($tupla = mysqli_fetch_assoc($resultado_profesores)) {
                    if (isset($idProfesorConsulta) && $idProfesorConsulta == $tupla["id_usuario"]) {
                        $idProfesorElegido = $tupla["id_usuario"];
                        $nombreProfesorSeleccionado = $tupla["nombre"];

                        echo "<option value=" . $tupla["id_usuario"] . " selected>" . $tupla["nombre"] . "</option>";
                    } else {
                        echo "<option value=" . $tupla["id_usuario"] . ">" . $tupla["nombre"] . "</option>";
                    }
                }
                ?>
            </select>
            <button type="submit" name="btnVerHorario">Ver horario</button>
        </form>
    <?php
    } else {
        echo "<p>No hay profesores introducidos en la base de datos</p>";
    }
    mysqli_free_result($resultado_profesores);
    ?>
    <?php
    // ? Mostrar el horario
    if (isset($_POST["btnVerHorario"]) || isset($_POST["btnEditar"]) || isset($_POST["btnQuitar"])) {
        $matriz_horario = [];

        while ($tupla = $resultado_horario->fetch_assoc()) {
            $dia = $tupla["dia"];
            $hora = $tupla["hora"];
            $nombreGrupo = $tupla["nombre"];

            // Si ya hay algo en esa celda, concatenamos con " / "
            if (isset($matriz_horario[$hora][$dia])) {
                $matriz_horario[$hora][$dia] .= "/" . $nombreGrupo;
            } else {
                $matriz_horario[$hora][$dia] = $tupla["nombre"];
            }
        }

        mysqli_free_result($resultado_horario);

        echo "<h3 class='text-center'>Horario del profesor: " . $nombreProfesorSeleccionado . "</h3>";

        echo "<table class='w-80 center'>";

        // Dias de la semana
        echo "<tr>";
        echo "<th></th>";
        for ($i = 1; $i <= 5; $i++) {
            echo "<th>" . DIAS[$i] . "</th>";
        }
        echo "</tr>";

        // Tabla
        foreach (HORAS as $key => $value) {
            if ($key == 4) {
                echo "<tr>";
                echo "<th>" . $value . "</th>";
                echo "<td colspan='5'>RECREO</td>";
                echo "</tr>";
            } else {
                echo "<tr>";
                echo "<th>" . $value . "</th>";
                for ($i = 1; $i <= 5; $i++) {
                    echo "<td>";
                    echo "<form action='index.php' method='post'>";

                    if (isset($matriz_horario[$key][$i])) {
                        echo $matriz_horario[$key][$i];
                    }
                    echo "<br>";
                    echo "<button type='submit' name='btnEditar' value='" . $idProfesorElegido . "' class='enlace'>Editar</button>";
                    echo "<input type='hidden' name='h_hora' value='" . $key . "'>";
                    echo "<input type='hidden' name='h_dia' value='" . $i . "'>";

                    echo "</form>";
                    echo "</td>";
                }
                echo "</tr>";
            }
        }

        echo "</table>";
    }
    ?>
    <?php
    // ? Mostrar la segunda tabla
    if (isset($_POST["btnEditar"]) || isset($_POST["btnQuitar"])) {
        echo "<h3>Editando la " . $_POST["h_hora"] . "º hora (" . HORAS["" . $_POST["h_hora"]] . ") del " . DIAS["" . $_POST["h_dia"]] . "</h3>";

        if (isset($mensaje_resultado)) {
            echo "<p>" . $mensaje_resultado . "</p>";
        }

        echo "<table>";

        echo "<tr>";
        echo "<th>Grupo</th>";
        echo "<th>Acción</th";
        echo "</tr>";

        while ($tupla = mysqli_fetch_assoc($resultado_clases)) {
            echo "<tr>";
            echo "<td>" . $tupla["nombre"] . "</td>";
            echo "<td>";
            echo "<form action='index.php' method='post'>";
            echo "<button type='submit' name='btnQuitar' value='" . $tupla["id_horario"] . "' class='enlace'>Quitar</button>";
            echo "<input type='hidden' name='h_hora' value='" . $_POST["h_hora"] . "'>";
            echo "<input type='hidden' name='h_dia' value='" . $_POST["h_dia"] . "'>";
            echo "<input type='hidden' name='h_profesor' value='" . $idProfesor . "'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";

    ?>
        <br>
        <form action="index.php" method="post">
            <select name="agregar" id="agregar">
                <?php
                while ($tupla = mysqli_fetch_assoc($resultado_grupos)) {
                    echo "<option value='" . $tupla["id_grupo"] . "'>" . $tupla["nombre"] . "</option>";
                }
                mysqli_free_result($resultado_grupos);
                ?>
            </select>
            <button type="submit" name="btnAgregar">Añadir</button>
        </form>
    <?php
    }
    ?>
</body>

</html>