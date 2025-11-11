<?php
if (isset($_POST["btnPasar"])) {
    $error_form = $_POST["contenido"] == "";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 1 PHP</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <h1>Pasar el contenido de un Textarea a <em>"Ficheros/resultado.txt"</em></h1>
    <form action="ejercicio1.php" method="post">
        <p>
            <textarea name="contenido" id="" rows="10" cols="40" placeholder="Teclee al menos una línea"><?php if (isset($_POST["contenido"])) echo $_POST["contenido"] ?></textarea>
            <?php if (isset($_POST["btnPasar"]) && $error_form) : ?>
                <br>
                <span class="error">* Debes teclear al menos una línea</span>
            <?php endif; ?>
        </p>
        <button type="submit" name="btnPasar">Pasar a fichero</button>
        <button type="submit" name="btnBorrar">Borrar Textarea</button>
    </form>

    <?php
    if (isset($_POST["btnPasar"]) && !$error_form) {
        @$fd = fopen("Ficheros/resultado.txt", "w");

        if (!$fd) {
            die("<p>No se ha podido crear el fichero Ficheros/resultado.txt</p></body></html>");
        }
        $lineas = explode(PHP_EOL, $_POST["contenido"]);
    ?>
        <h3>Fichero generado con éxito (<a href="Ficheros/resultado.txt" target="_blank">DESCARGAR</a>)</h3>
        <h3>Estas son las líneas de su contenido</h3>
        <ol>
            <?php
            // Aprovechamos el bucle para mostrar los li tambien
            for ($i = 0; $i < count($lineas); $i++) {
                fputs($fd, $lineas[$i] . PHP_EOL);
                echo "<li>" . $lineas[$i] . "</li>";
            }
            ?>
        </ol>
    <?php
        fclose($fd);
    }
    ?>
</body>

</html>