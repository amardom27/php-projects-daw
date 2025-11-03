<?php
require("../const_globales/env.php");
const NOMBRE_BD = "bd_horarios_exam";

require "./src/func_conts.php";

// Conectarse a la base de datos
try {
    @$conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE_BD);
    mysqli_set_charset($conexion, 'utf8');
} catch (Exception $e) {
    die(error_page("<h1>No se ha podido conectar a la base de datos</h1><p> " . $e->getMessage() . "</p>   "));
}

// Consulta para traer los nombres de los profesores
try {
    $consulta = "select id_usuario, nombre from usuarios";
    $resultado_profesores = mysqli_query($conexion, $consulta);
} catch (Exception $e) {
    mysqli_close($conexion);
    die(error_page("<h1>No se ha podido realizar la consulta</h1><p>" . $e->getMessage() . "</p>"));
}

// Consulta para traer los datos del horario del profesor elegido (id = $_POST["profesor"])
if (isset($_POST["profesor"])) {
    try {
        $consulta = "
            select dia, hora, grupos.nombre 
            from horario_lectivo
            join grupos on horario_lectivo.grupo = grupos.id_grupo 
            where usuario = '" . $_POST["profesor"] . "'
        ";
        $resultado_horario = mysqli_query($conexion, $consulta);

        while ($tupla = mysqli_fetch_assoc($resultado_horario)) {
            // Si ya esta asignado tengo que concatenar
            if (isset($datos_horario[$tupla["dia"]][$tupla["hora"]])) {
                $datos_horario[$tupla["dia"]][$tupla["hora"]] .= "/" . $tupla["nombre"];
            } else {
                $datos_horario[$tupla["dia"]][$tupla["hora"]] = $tupla["nombre"];
            }
        }
        mysqli_free_result($resultado_horario);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page("<h1>No se ha podido realizar la consulta</h1><p>" . $e->getMessage() . "</p>"));
    }
}

// Consulta para traer los datos de los grupos cuando se pulsa editar
if (isset($_POST["dia"])) {
    try {
        $consulta = "
            select id_horario, dia, hora, nombre, grupo 
            from horario_lectivo 
            join grupos on horario_lectivo.grupo = grupos.id_grupo 
            where usuario = '" . $_POST["profesor"] . "'  
            and dia = '" . $_POST["dia"] . "'
            and hora = '" . $_POST["hora"] . "'
        ";
        $resultado_grupos = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page("<h1>No se ha podido realizar la consulta</h1><p>" . $e->getMessage() . "</p>"));
    }
}
// $consulta = "
//             select grupos.* 
//             from grupos 
//             where usuario = id_grupo not it (la anterior consulta)
//         ";

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solucion Ex 2 Miguel</title>
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
    // ? Mostrar el formulario del profesor
    if (mysqli_num_rows($resultado_profesores) > 0) {
    ?>
        <form action="index.php" method="post">
            <label for="profesor">Horario del profesor: </label>
            <select name="profesor" id="profesor">
                <?php
                while ($tupla = mysqli_fetch_assoc($resultado_profesores)) {
                    if (isset($_POST["profesor"]) && $_POST["profesor"] == $tupla["id_usuario"]) {
                        echo "<option value=" . $tupla["id_usuario"] . " selected>" . $tupla["nombre"] . "</option>";
                        $nombreProfesor = $tupla["nombre"];
                    } else {
                        echo "<option value=" . $tupla["id_usuario"] . ">" . $tupla["nombre"] . "</option>";
                    }
                }
                mysqli_free_result($resultado_profesores);
                ?>
            </select>
            <button type="submit" name="btnVerHorario">Ver horario</button>
        </form>
    <?php
    } else {
        echo "<p>No hay profesores introducidos en la base de datos</p>";
    }

    // ? Mostrar la tabla de horario
    if (isset($_POST["profesor"])) {
        echo "<h3 class='text-center'>Horario del profesor: " . $nombreProfesor . "</h3>";

        echo "<table class='w-80 center'>";

        echo "<tr>";
        echo "<th></th>";
        for ($i = 1; $i <= 5; $i++) {
            echo "<th>" . DIAS[$i] . "</th>";
        }
        echo "</tr>";

        foreach (HORAS as $hora_num => $hora_txt) {
            echo "<tr>";
            if ($hora_num == 4) {
                echo "<th>" . $hora_txt . "</th>";
                echo "<td colspan='5'>RECREO</td>";
            } else {
                echo "<th>" . HORAS[$hora_num] . "</th>";

                for ($dias = 1; $dias <= 5; $dias++) {
                    echo "<td>";
                    echo "<form action='index.php' method='post'>";

                    if (isset($datos_horario[$dias][$hora_num])) {
                        echo $datos_horario[$dias][$hora_num];
                        echo "<br>";
                    }

                    echo "<button type='submit' name='btnEditar' class='enlace'>Editar</button>";

                    // Poner el mismo nombre para que profesor exista y vuelva a hacer la tabla
                    echo "<input type='hidden' name='profesor' value='" . $_POST["profesor"] . "'>";
                    echo "<input type='hidden' name='dia' value='" . $dias . "'>";
                    echo "<input type='hidden' name='hora' value='" . $hora_num . "'>";

                    echo "</form>";
                    echo "</td>";
                }
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    // ? Mostramos la segunda tabla
    if (isset($_POST["dia"])) {
        if ($_POST["hora"] > 4) {
            echo "<h3>Editando la " . ($_POST["hora"]  - 1) . "º hora (" . HORAS["" . $_POST["hora"]] . ") del " . DIAS["" . $_POST["dia"]] . "</h3>";
        } else {
            echo "<h3>Editando la " . $_POST["hora"] . "º hora (" . HORAS["" . $_POST["hora"]] . ") del " . DIAS["" . $_POST["dia"]] . "</h3>";
        }

        echo "<table>";
        echo "<tr>";
        echo "<th>Grupo</th><th>Acción</th>";
        echo "</tr>";

        while ($tupla = mysqli_fetch_assoc($resultado_grupos)) {
            echo "<tr>";
            echo "<td>" . $tupla["nombre"] . "</td>";
            echo "<td>";
            echo "<form action='index.php' method='post'>";
            echo "<button type='submit' name='btnQuitar' value='' class='enlace'>Quitar</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        mysqli_free_result($resultado_grupos);

        echo "</table>";

        // ! Hay que controlar los grupos que mostramos en el select
    }
    ?>
</body>

</html>