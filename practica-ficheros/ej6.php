<?php
const URL = "http://dwese.icarosproject.com/PHP/datos_ficheros.txt";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 6 - Ficheros</title>
    <style>
        table,
        td,
        th {
            border: 1px solid black;
        }

        table {
            border-collapse: collapse;
            width: 90%;
            margin: 0 auto;
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Ejercicio 6 - Ficheros</h1>
    <?php
    @$fd = fopen(URL, "r");
    if (!$fd) {
        // Se acaba aqui el programa si no puede leer el fichero
        die("<h3>No se ha podido abrir el fichero <em>" . URL . "</em></h3></body></html>");
    }
    // Leer la primera linea porque es especial
    $linea = fgets($fd);
    $datos_para_th = explode("\t", $linea);
    $n_col = count($datos_para_th);
    ?>
    <form action="ej6.php" method="post">
        <p>
            <label for="pais">Seleccione un país: </label>
            <select name="pais" id="pais">
                <?php
                while ($linea = fgets($fd)) {
                    // Partimos la linea
                    $datos_linea = explode("\t", $linea);
                    // De la primera posicion, la partimos por coma para quedarnos con el final
                    $datos_primera_linea = explode(",", $datos_linea[0]);

                    // Comprobacion para persistir la eleccion
                    if (isset($_POST["pais"]) && $_POST["pais"] == end($datos_primera_linea)) {
                        echo "<option value='" . end($datos_primera_linea) . "' selected>" . end($datos_primera_linea) . "</option>";

                        // Nos guardamos el que esta seleccionado para mostrarlos en la tabla
                        $datos_a_mostrar = $datos_linea;
                    } else {
                        echo "<option value='" . end($datos_primera_linea) . "'>" . end($datos_primera_linea) . "</option>";
                    }
                }
                // NO OLVIDAR CERRAR
                fclose($fd);
                ?>
            </select>
        </p>
        <button type="submit" name="btnEnviar">Buscar</button>
    </form>
    <?php
    if (isset($_POST["btnEnviar"])) {
        echo "<h3>PIB per cápita de " . $_POST["pais"] . "</h3>";
    ?>
        <table>
            <tr>
                <?php
                for ($i = 1; $i < $n_col; $i++) {
                    echo "<th>" . $datos_para_th[$i] . "</th>";
                }
                ?>
            </tr>
            <tr>
                <?php
                for ($i = 1; $i < $n_col; $i++) {
                    if (isset($datos_a_mostrar[$i])) {
                        echo "<td>" . $datos_a_mostrar[$i] . "</td>";
                    } else {
                        echo "<td></td>";
                    }
                }
                ?>
            </tr>
        </table>
    <?php
    }
    ?>
</body>

</html>