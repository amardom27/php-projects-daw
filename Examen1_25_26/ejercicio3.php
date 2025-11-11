<?php
const SERVIDOR = "localhost";
const USUARIO = "jose";
const CLAVE = "josefa";
const NOMBRE_BD = "bd_exam_colegio";

function error_page($body) {
    return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 3</title>
</head>
<body>
    <p>' . $body . ' </p>
</body>
</html>';
}

try {
    $conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE_BD);
    mysqli_set_charset($conexion, "utf8");
} catch (Exception $e) {
    die(error_page($e->getMessage()));
}

try {
    $consulta = "select * from alumnos";
    $resultado_alu = mysqli_query($conexion, $consulta);
} catch (Exception $e) {
    mysqli_close($conexion);
    die(error_page($e->getMessage()));
}

if (mysqli_num_rows($resultado_alu) <= 0) {
    die(error_page("<p>En estos momentos no tenemos ningún alumno en la BD</p>"));
}

try {
    $consulta = "select * from asignaturas";
    $resultado_asig = mysqli_query($conexion, $consulta);
} catch (Exception $e) {
    mysqli_close($conexion);
    die(error_page($e->getMessage()));
}

if (mysqli_num_rows($resultado_asig) <= 0) {
    die(error_page("<p>En estos momentos no tenemos ningún alumno en la BD</p>"));
}

// select * from notas join alumnos on notas.cod_alu = alumnos.cod_alu where cod_asig = 101;
// select nombre, nota from notas join alumnos on notas.cod_alu = alumnos.cod_alu where cod_asig = 101; 
// select nombre from alumnos where alumnos.cod_alu not in (select notas.cod_alu from notas join alumnos on notas.cod_alu = alumnos.cod_alu where cod_asig = 101); 
if (isset($_POST["asignatura"])) {
    try {
        $consulta = "select nombre, nota from notas join alumnos on notas.cod_alu = alumnos.cod_alu where cod_asig = '" . $_POST["asignatura"] . "'";
        $resultado_notas = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page($e->getMessage()));
    }

    try {
        $consulta = "select nombre from alumnos 
        where alumnos.cod_alu not in (select notas.cod_alu from notas join alumnos on notas.cod_alu = alumnos.cod_alu where cod_asig = '" . $_POST["asignatura"] . "')";
        $resultado_no_notas = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page($e->getMessage()));
    }
}

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 3</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <h1>Notas por asignatura de los alumnos</h1>
    <form action="ejercicio3.php" method="post">
        <label for="asignatura">Seleccione una asignatura: </label>
        <select name="asignatura" id="asignatura">
            <?php
            while ($tupla = mysqli_fetch_assoc($resultado_asig)) {
                if (isset($_POST["asignatura"]) && $tupla["cod_asig"] == $_POST["asignatura"]) {
                    $nombreAsig = $tupla["denominacion"];
                    echo "<option selected value='" . $tupla["cod_asig"] . "'>" . $tupla["denominacion"] . "</option>";
                } else {
                    echo "<option value='" . $tupla["cod_asig"] . "'>" . $tupla["denominacion"] . "</option>";
                }
            }
            ?>
        </select>
        <button type="submit" name="btnVerNotas">Ver notas</button>
    </form>
    <?php
    if (isset($_POST["asignatura"])) {
        echo "<h2>Nota de los alumnos en la Asignatura de " . $nombreAsig . "</h2>";

        echo "<table>";
        echo "<tr>";
        echo "<th>Nombre</th>";
        echo "<th>Nota</th>";
        echo "</tr>";

        while ($tupla = mysqli_fetch_assoc($resultado_notas)) {
            echo "<tr>";
            echo "<td>" . $tupla["nombre"] . "</td>";
            echo "<td>" . $tupla["nota"] . "</td>";
            echo "</tr>";
        }
        mysqli_free_result($resultado_notas);

        echo "</table>";

        if (mysqli_num_rows($resultado_no_notas) > 0) {
            echo "<h3>Listado de alumnos que les queda aún la asignatura " . $nombreAsig . " por calificar:</h3>";
            echo "<ol>";

            while ($tupla = mysqli_fetch_assoc($resultado_no_notas)) {
                echo "<li>" . $tupla["nombre"] . "</li>";
            }
            mysqli_free_result($resultado_no_notas);

            echo "</ol>";
        } else {
            echo "<p>Todos los alumnos tiene calificada la asignatura de " . $nombreAsig . "</p>";
        }
    }
    ?>
</body>

</html>