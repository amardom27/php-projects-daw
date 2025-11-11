<?php
const SERVIDOR = "localhost";
const USUARIO = "jose";
const CLAVE = "josefa";
const NOMBRE_BD = "bd_exam_colegio";

function error_page($title, $body) {
    return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $title . '</title>
</head>
<body>' . $body . '</body>
</html>';
}

try {
    @$conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE_BD);
    mysqli_set_charset($conexion, "utf8");
} catch (Exception $e) {
    die(error_page("Ejercicio 3 PHP", "<h1>Ejercicio 3</h1><p>No se ha podido conectar a la base de datos: " . $e->getMessage()) . "</p>");
}

try {
    $consulta = "select cod_alu, nombre from alumnos";
    $resultado_alumnos = mysqli_query($conexion, $consulta);
} catch (Exception $e) {
    mysqli_close($conexion);
    die(error_page("Ejercicio 3 PHP", "No se ha podido realizar la consulta: " . $e->getMessage()));
}

// Si no hay nada en la anterior me ahorro una consulta
if (mysqli_num_rows($resultado_alumnos) > 0) {
    try {
        $consulta = "select * from asignaturas";
        $resultado_asignaturas = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        // Limpiar la consulta de alumnos
        mysqli_free_result($resultado_alumnos);

        mysqli_close($conexion);
        die(error_page("Ejercicio 3 PHP", "No se ha podido realizar la consulta: " . $e->getMessage()));
    }

    if (mysqli_num_rows($resultado_alumnos) > 0 && isset($_POST["asignatura"])) {
        try {
            // select nombre, nota from notas join alumnos on notas.cod_alu = alumnos.cod_alu where notas.cod_asig = 103;
            $consulta = "select notas.cod_alu, nombre, nota from notas join alumnos on notas.cod_alu = alumnos.cod_alu where cod_asig = '" . $_POST["asignatura"] . "'";
            $resultado_notas = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_free_result($resultado_alumnos);
            mysqli_free_result($resultado_asignaturas);

            mysqli_close($conexion);
            die(error_page("Ejercicio 3 PHP", "No se ha podido realizar la consulta: " . $e->getMessage()));
        }
    }
}

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 3 PHP</title>
</head>

<body>
    <h1>Notas por asignaturas de los Alumnos</h1>
    <?php if (!isset($resultado_asignaturas)): ?>
        <p>No hay alumnos en la base de datos.</p>
    <?php elseif (mysqli_num_rows($resultado_asignaturas) <= 0):
        mysqli_free_result($resultado_alumnos);
        mysqli_free_result($resultado_asignaturas);
    ?>
        <p>No hay asignaturas en la base de datos.</p>
    <?php else: ?>
        <form action="ejercicio3.php" method="post">
            <p>
                <label for="asignatura">Seleccione una asignatura: </label>
                <select name="asignatura" id="asignatura">
                    <?php while ($tupla = mysqli_fetch_assoc($resultado_asignaturas)): ?>
                        <?php if (isset($_POST["asignatura"]) && $_POST["asignatura"] == $tupla["cod_asig"]):
                            $nomAsigSelecionada = $tupla["denominacion"];
                        ?>
                            <option selected value="<?= $tupla["cod_asig"] ?>"><?= $tupla["denominacion"] ?></option>
                        <?php else: ?>
                            <option value="<?= $tupla["cod_asig"] ?>"><?= $tupla["denominacion"] ?></option>
                        <?php endif ?>
                    <?php endwhile;
                    mysqli_free_result($resultado_asignaturas);
                    ?>
                </select>
                <button type="submit" name="btnVerNotas">Ver notas</button>
            </p>
        </form>
    <?php endif; ?>
    <?php
    if (isset($_POST["asignatura"])): ?>
        <h2>Notas de los alumnos en la asignatura: <?= $nomAsigSelecionada ?></h2>
        <table border="1">
            <tr>
                <th>Nombre</th>
                <th>Nota</th>
            </tr>
            <?php
            // Guardamos los alumnos que tienen nota
            $ids_alumnos_calificados = [];
            while ($tupla = mysqli_fetch_assoc($resultado_notas)):
                $ids_alumnos_calificados[] = $tupla["cod_alu"];
            ?>
                <tr>
                    <td><?= $tupla["nombre"] ?></td>
                    <td><?= $tupla["nota"] ?></td>
                </tr>
            <?php
            endwhile;
            ?>
        </table>
        <?php
        if (mysqli_num_rows($resultado_alumnos) != count($ids_alumnos_calificados)): ?>
            <h3>Listado de los alumnos que no estan calificados en <?= $nomAsigSelecionada ?></h3>
            <ol>
                <?php
                mysqli_data_seek($resultado_alumnos, 0);
                while ($tupla = mysqli_fetch_assoc($resultado_alumnos)):
                    if (!in_array($tupla["cod_alu"], $ids_alumnos_calificados)):
                ?>
                        <li><?= $tupla["nombre"] ?></li>
                    <?php endif; ?>
                <?php endwhile; ?>
            </ol>
        <?php else: ?>
            <p>Todos los alumnos tiene calificada la asignatura: <?= $nomAsigSelecionada ?></p>
        <?php
        endif;
        mysqli_free_result($resultado_alumnos);
        ?>
    <?php endif; ?>
</body>

</html>