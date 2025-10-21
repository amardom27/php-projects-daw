<?php
const NOMBRE = "bd_teoria";
require("../const_globales/env.php");

function error_page($title, $body) {
    $html = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>' . $title . '</title>
            </head>
            <body>
            ' . $body . '
            </body>
            </html>';
    return $html;
}

// Realizamos la conexion a la base de datos
try {
    @$conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE);
    mysqli_set_charset($conexion, "utf8");
} catch (Exception $e) {
    // die("<p>Error, no se ha podido conectar a la base de datos " . $e->getMessage() . "</p></body></html>");
    die(error_page("Practica BBDD", "<p>Error, no se ha podido conectar a la base de datos " . $e->getMessage()));
}

// Realizamos la consulta a la base de datos
try {
    $consulta = "select cod_asig, denominacion from t_asignatura";
    $resultado = mysqli_query($conexion, $consulta);
} catch (Exception $e) {
    mysqli_close($conexion);
    // die("<p>Error, no se ha podido realizar la consulta " . $e->getMessage() . "</p></body></html>");
    die(error_page("Practica BBDD", "<p>Error, no se ha podido realizar la consulta " . $e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practica BBDD</title>
    <style>
        table,
        td,
        th {
            border: 1px solid black;
            padding: 4px;
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
    // Comprobacion que tenemos tuplas
    if (mysqli_num_rows($resultado) > 0) {
    ?>
        <h1>Practica BBDD</h1>
        <form action="index.php" method="post">
            <label for="asig">Elija la asignatura: </label>
            <select name="asig" id="asig">
                <?php while ($tupla = mysqli_fetch_assoc($resultado)) : ?>
                    <?php
                    $selected = "";
                    if (isset($_POST["asig"]) && $_POST["asig"] == $tupla["cod_asig"]) {
                        $selected = "selected";
                        $denomi = $tupla["denominacion"];
                    }
                    ?>
                    <option value="<?= $tupla["cod_asig"] ?>" <?= $selected ?>>
                        <?= htmlspecialchars($tupla["denominacion"]) ?>
                    </option>
                    <?php mysqli_free_result($resultado); // Liberar cuando gastemos las tuplas de un select 
                    ?>
                <?php endwhile; ?>
            </select>
            <p>
                <button type=" submit" name="btnEnviar">Ver notas</button>
            </p>
        </form>
    <?php
    } else {
        echo "<h3>La base de datos no tiene aún ninguna asignatura</h3>";
    }
    // En este caso tambien se puede preguntar por el $_POST["asig"]
    // TODO: MOVER ESTO ARRIBA
    if (isset($_POST["btnEnviar"])) {
        try {
            $consulta = "
                SELECT 
                    t_alumnos.nombre, 
                    t_notas.nota
                FROM 
                    t_notas
                JOIN 
                    t_alumnos ON t_notas.cod_alu = t_alumnos.cod_alu
                WHERE 
                    t_notas.cod_asig = " . $_POST["asig"] . ";";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die("<p>Error, no se ha podido realizar la consulta " . $e->getMessage() . "</p></body></html>");
        }
        mysqli_close($conexion);
    ?>
        <h3>Información de las notas de <?= $denomi ?></h3>
        <table>
            <tr>
                <th>Nombre Alumno</th>
                <th>Nota</th>
            </tr>
            <?php while ($tupla = mysqli_fetch_assoc($resultado)) : ?>
                <tr>
                    <td><?= htmlspecialchars($tupla["nombre"]) ?></td>
                    <td><?= htmlspecialchars($tupla["nota"]) ?></td>
                </tr>
                <?php mysqli_free_result($resultado); // Liberar cuando gastemos las tuplas de un select 
                ?>
            <?php endwhile; ?>
        </table>
    <?php
    }
    ?>
</body>

</html>