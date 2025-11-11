<?php
if (isset($_POST["btnPasar"])) {
    $error_form = $_POST["texto"] == "";

    if (!$error_form) {
        $fd = fopen("Ficheros/resultado.txt", "w");

        fputs($fd, $_POST["texto"]);

        fclose($fd);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 1</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <h1>Pasar el contendio de un Textarea a "Ficheros/resultado.txt"</h1>
    <form action="ejercicio1.php" method="post">
        <textarea name="texto" id="texto" placeholder="Teclee al menos una línea" rows="10" cols="40"></textarea>
        <?php
        if (isset($_POST["btnPasar"]) && $error_form) {
            echo "<br>";
            echo "<span class='error'>* Debe al menos rellenar una línea.</span>";
        }
        ?>
        <p>
            <button type="submit" name="btnPasar">Pasar e fichero</button>
            <button type="submit">Borrar Textarea</button>
        </p>
        <?php if (isset($_POST["btnPasar"]) && !$error_form):
            $fd = fopen("Ficheros/resultado.txt", "r");
        ?>
            <h3>Fichero generado con éxito (<a href="Ficheros/resultado.txt">Descargar</a>)</h3>
            <h3>Estas son las líneas de su contenido:</h3>
            <ol>
                <?php
                while ($linea = fgets($fd)) {
                    echo "<li>" . $linea . "</li>";
                }
                ?>
            </ol>
        <?php
            fclose($fd);
        endif;
        ?>
    </form>

</body>

</html>