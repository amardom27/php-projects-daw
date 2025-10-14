<?php
const URL = "http://dwese.icarosproject.com/PHP/datos_ficheros.txt";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 5 - Ficheros</title>
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
    <h1>Ejercicio 5 - Ficheros</h1>
    <?php
    @$fd = fopen(URL, "r");
    if (!$fd) {
        // Se acaba aqui el programa si no puede leer el fichero
        die("<h3>No se ha podido abrir el fichero <em>" . URL . "</em></h3></body></html>");
    }
    // Leer la primera linea porque es especial
    $linea = fgets($fd);
    ?>
    <table>
        <caption>PIB per cápita de los países europeos</caption>
        <tr>
            <?php
            $datos_linea = explode("\t", $linea);
            $n_col = count($datos_linea);

            // foreach ($datos_linea as $key => $value) {
            //     echo "<th>$value</th>";
            // }
            for ($i = 0; $i < $n_col; $i++) {
                echo "<th>" . $datos_linea[$i] . "</th>";
            }
            ?>
        </tr>
        <?php
        while ($linea = fgets($fd)) {
            echo "<tr>";
            $datos_linea = explode("\t", $linea);

            // foreach ($datos_linea as $key => $value) {
            //     if ($key === 0) {
            //         echo "<th>$value</th>";
            //     } else {
            //         echo "<td>$value</td>";
            //     }
            // }
            echo "<th>" . $datos_linea[0] . "</th>";
            for ($i = 1; $i < $n_col; $i++) {
                if (isset($datos_linea[$i])) {
                    echo "<td>" . $datos_linea[$i] . "</td>";
                } else {
                    echo "<td></td>";
                }
            }
            echo "</tr>";
        }
        ?>
    </table>
    <?php
    fclose($fd);
    ?>
</body>

</html>