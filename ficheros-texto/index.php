<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teoria Ficheros</title>
</head>

<body>
    <h1>Teoria de ficheros</h1>
    <?php
    // @ para cuando vamos a controlar el fallo, que no pete en la pagina

    // r -> lectura
    // w -> borra y escribe de nuevo (al principio)
    // a -> pega al final del fichero
    // w / a -> crea el archivo si no existe !!
    // @$fd = fopen("prueba.txt", "r");
    // if (!$fd) {
    //     // die == exit
    //     die("<p>El fichero no existe</p></body></html>");
    // }
    // fclose($fd);

    if (file_exists("prueba.txt")) {
        $fd = fopen("prueba.txt", "r");
    } else {
        die("<p>El fichero no existe</p></body></html>");
    }
    // fgets -> para leer una linea
    $linea = fgets($fd);
    echo "<p>" . $linea . "</p>";

    $linea = fgets($fd);
    echo "<p>" . $linea . "</p>";

    $linea = fgets($fd);
    echo "<p>" . $linea . "</p>";

    // Si se han gastado todas las lineas devuelve false
    // Para PHP false es "" asi que pone una p vacia
    $linea = fgets($fd);
    echo "<p>" . $linea . "</p>";

    // fseek -> volver al principio
    // El segundo parametro son bytes, no lineas !!
    fseek($fd, 0);

    $linea = fgets($fd);
    echo "<p>" . $linea . "</p>";

    $linea = fgets($fd);
    echo "<p>" . $linea . "</p>";

    fseek($fd, 0);

    // feof -> final del fichero (end of file)
    while (!feof($fd)) {
        $linea = fgets($fd);
        echo "<p>" . $linea . "</p>";
    }

    fseek($fd, 0);

    while ($linea = fgets($fd)) {
        echo "<p>" . $linea . "</p>";
    }

    fclose($fd);

    // Hemos tenido que cambiar el archivo porque lo hemos creado a mano
    // y no tiene los permisos
    @$fd = fopen("prueba2.txt", "w");
    if (!$fd) {
        // die == exit
        die("<p>El fichero no existe</p></body></html>");
    }

    // Escribir en un fichero (ambas funciones lo hacen)
    // PHP_EOL -> constante PHP para poner un salto de linea (igual que \n)
    fputs($fd, "Hola segundo fichero" . PHP_EOL);
    fwrite($fd, "Segunda linea" . PHP_EOL);

    fclose($fd);

    @$fd = fopen("prueba2.txt", "a");
    if (!$fd) {
        // die == exit
        die("<p>El fichero no existe</p></body></html>");
    }

    fputs($fd, "Otra linea mas");

    fclose($fd);

    // unlink -> borrar un archivo
    // unlink("prueba2.txt");

    echo "<h2>Lectura entera de un fichero</h2>";
    // Obtiene todo el fichero de una vez
    $todo = file_get_contents("prueba.txt");
    echo "<pre>" . $todo . "</pre>";

    // $web = file_get_contents("https://www.google.es");
    $web = file_get_contents("https://jsonplaceholder.typicode.com/posts/1");
    echo "<pre>" . $web . "</pre>";
    ?>
</body>

</html>