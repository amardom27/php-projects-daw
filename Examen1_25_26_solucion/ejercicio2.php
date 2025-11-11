<?php
require "constantes.php";

function poner_columnas($columnas) {
    $respuesta = $columnas[0];
    for ($i = 1; $i < count($columnas); $i++) {
        $respuesta .= "," . $columnas[$i];
    }
    return $respuesta;
}

function poner_valores($valores) {
    $respuesta = "'" . $valores[0] . "'";
    for ($i = 1; $i < count($valores); $i++) {
        $respuesta .= ",'" . $valores[$i] . "'";
    }
    return $respuesta;
}

if (isset($_POST["btnRellenar"]) || isset($_POST["btnBorrar"])) {
    $error_form = $_FILES["fichero"]["name"] != ""
        && (
            $_FILES["fichero"]["error"]
            || $_FILES["fichero"]["type"] = "text/plain"
            || $_FILES["fichero"]["size"] > 2 * 1024 * 1024
        );

    if (!$error_form && $_FILES["fichero"]["name"] != "") {
        // Empezar a hacer el ejercicio
        @$contenido_fichero = file_get_contents($_FILES["fichero"]["tmp_name"]);

        if (!$contenido_fichero) {
            $mesaje_accion = "No se ha podido abrir el fichero que se ha subido.";
        } else {
            try {
                @$conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE_BD);
                mysqli_set_charset($conexion, "utf8");
            } catch (Exception $e) {
                die(error_page("Ejercicio 2 PHP", "<h1>Ejercicio 2</h1><p>No se ha podido conectar a la base de datos: " . $e->getMessage()) . "</p>");
            }

            $lineas_fichero = explode(PHP_EOL, $contenido_fichero);

            // Nombre de la tabla y los campos
            $tabla = trim($lineas_fichero[0]);
            $datos_columnas = explode(";", $lineas_fichero[1]);

            if (isset($_POST["btnBorrar"])) {
                try {
                    $consulta = "delete from '" . $tabla . "'";
                    mysqli_query($conexion, $consulta);
                } catch (Exception $e) {
                    mysqli_close($conexion);
                    die(error_page("Ejercicio 2 PHP", "<h1>Ejercicio 2</h1><p>No se ha podido conectar a la base de datos: " . $e->getMessage()) . "</p>");
                }
            } else {
                for ($i = 2; $i < count($lineas_fichero); $i++) {
                    $datos_insertar = explode(";", $lineas_fichero[$i]);

                    try {
                        $consulta = "insert into '" . $tabla . "' (" . poner_columnas($datos_columnas) . ") values (" . poner_valores($datos_insertar) . ")";
                        // echo "<p>" . $consulta . "</p>";
                        mysqli_query($conexion, $consulta);
                    } catch (Exception $e) {
                        mysqli_close($conexion);
                        die(error_page("Ejercicio 2 PHP", "<h1>Ejercicio 2</h1><p>No se ha podido conectar a la base de datos: " . $e->getMessage()) . "</p>");
                    }
                }
            }

            // Independientemente del boton pulsado
            try {
                $consulta = "select * from " . $tabla;
                $resultado_tabla = mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                mysqli_close($conexion);
                die(error_page("Ejercicio 2 PHP", "<h1>Ejercicio 2</h1><p>No se ha podido conectar a la base de datos: " . $e->getMessage()) . "</p>");
            }

            mysqli_close($conexion);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 2 PHP</title>
    <style>
        .error {
            color: red;
        }

        table,
        td,
        th {
            border: 1px solid black;
        }

        table {
            border-collapse: collapse;
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Rellenar/Borra los datos de la base de datos <em>bd_exam_colegio</em></h1>
    <form action="ejercicio2.php" method="post" enctype="multipart/form-data">
        <p>
            <label for="fichero">Seleccione el fichero de texto: </label>
            <input type="file" name="fichero" id="fichero">
            <?php
            if ((isset($_POST["btnRellenar"]) || isset($_POST["btnBorrar"])) && $error_form):
                if ($_FILES["fichero"]["error"]): ?>
                    <span class="error">* Error en la subida del fichero al servidor</span>
                <?php elseif ($_FILES["fichero"]["type"] != "text/plain"): ?>
                    <span class="error">* El fichero no es un fichero de texto.</span>
                <?php elseif ($_FILES["fichero"]["size"] > 2 * 1024 * 1024): ?>
                    <span class="error">* El fichero pesa más de lo permitido.</span>
                <?php endif; ?>
            <?php endif; ?>
        </p>
        <p>
            <button type="submit" name="btnRellenar">Rellenar tabla</button>
            <button type="submit" name="btnBorrar">Borrar Tabla</button>
        </p>
    </form>
    <?php
    // Error muy raro de cuando no podemos leer el fichero subido
    if (isset($mesaje_accion)): ?>
        <p><?= $mesaje_accion ?></p>
    <?php endif; ?>

    <?php if ((isset($_POST["btnRellenar"]) || isset($_POST["btnBorrar"])) && !$error_form && $_FILES["fichero"]["name"] != ""): ?>
        <?php if (isset($_POST["btnRellenar"])): ?>
            <h3>Volcado del fichero a la tabla <?= $tabla ?> realizado con éxito.</h3>
        <?php else: ?>
            <h3>Borrado de datos de la tabla <?= $tabla ?> realizado con éxito.</h3>
        <?php endif; ?>

        <table>
            <caption><?= $tabla ?></caption>
            <tr>
                <?php for ($i = 0; $i < count($datos_columnas); $i++): ?>
                    echo <th><?= $datos_columnas[$i] ?></th>";
                <?php endfor; ?>
            </tr>

            <?php while ($tupla = mysqli_fetch_assoc($resultado_tabla)): ?>
                <tr>
                    <?php foreach ($tupla as $valor): ?>
                        <td><?= $valor ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</body>

</html>