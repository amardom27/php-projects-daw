<?php
function es_numerico($num) {
    return is_numeric($num);
}

function es_entero($num) {
    // Esta funcion valida si es un numero entero
    // Devuelve false para numeros decimales y si tiene algun caracter (2.4, 123ab)
    return filter_var($num, FILTER_VALIDATE_INT) !== false;
}

if (isset($_POST["btnEnviar"])) {
    $numero = trim($_POST["num"]);

    $error_form = $numero === "" || !es_numerico($numero) || !es_entero($numero) || $numero < 1 || $numero > 10;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 1 - Ficheros</title>
    <style>
        .error {
            color: #dc3545;
        }

        .success {
            color: #28a745;
        }

        .info {
            color: #007bff;
        }
    </style>
</head>

<body>
    <h1>Ejercicio 1</h1>
    <p>Introduzca un número para obtener su tabla de multiplicar.</p>
    <form action="" method="post">
        <label for="num">Numero: </label>
        <input type="text" name="num" id="num" value="<?php if (isset($_POST["num"])) echo $numero; ?>">
        <?php
        if (isset($_POST["btnEnviar"]) && $error_form) {
            if ($numero === "") {
                echo "<span class='error'>* Debe rellenar el campo.</span>";
            } elseif (!es_numerico($numero)) {
                echo "<span class='error'>* Solo se admiten números.</span>";
            } elseif (!es_entero($numero)) {
                echo "<span class='error'>* Solo se adimiten números enteros.</span>";
            } elseif ($numero < 1 || $numero > 10) {
                echo "<span class='error'>* Solo se admiten números entre 1 y 10 (incluidos).</span>";
            }
        }
        ?>
        <p>
            <button type="submit" name="btnEnviar">Enviar</button>
        </p>
    </form>
    <?php
    if (isset($_POST["btnEnviar"]) && !$error_form) {
        $ruta_fichero = "tablas/tabla_" . $numero . ".txt";

        if (file_exists($ruta_fichero)) {
            echo "<p class='info'>El fichero se ha generado correctamente.</p>";
        } else {
            @$fd = fopen($ruta_fichero, "w");

            if (!$fd) {
                echo "<p class='error'>No se ha podido crear el fichero: $ruta_fichero</p>";
            } else {
                // Generamos la tabla
                for ($i = 0; $i <= 10; $i++) {
                    $res = "$i x $numero = " . ($i * $numero) . PHP_EOL;
                    fputs($fd, $res);
                }
                fclose($fd);
                echo "<p class='success'>El fichero se ha generado correctamente.</p>";
            }
        }
    }
    ?>
</body>

</html>