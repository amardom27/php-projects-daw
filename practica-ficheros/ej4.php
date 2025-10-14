<?php
const SIZE = 2621440; // 2.5 * 1024 * 1024

if (isset($_POST["btnEnviar"])) {
    $error_fichero = $_FILES["fichero"]["name"] == ""
        || $_FILES["fichero"]["error"] != 0
        || $_FILES["fichero"]["type"] != "text/plain"
        || $_FILES["fichero"]["size"] > SIZE;

    $error_form = $error_fichero;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 4 - Ficheros</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <h1>Ejercicio 4 - Ficheros</h1>
    <form action="ej4.php" method="post" enctype="multipart/form-data">
        <p>
            <label for="fichero">Seleccione un fichero para contar las palabras (Max: 2.5 MB)</label>
            <input type="file" name="fichero" id="fichero" accept="text/plain">
            <?php
            if (isset($_POST["btnEnviar"]) && $error_form) {
                if ($_FILES["fichero"]["name"] === "") {
                    echo "<span class='error'>* Debe seleccionar un fichero</span>";
                } elseif ($_FILES["fichero"]["error"] !== 0) {
                    echo "<span class='error'>* Error subiendo el fichero.</span>";
                } elseif ($_FILES["fichero"]["type"] !== "text/plain") {
                    echo "<span class='error'>* El fichero debe ser de tipo texto plano.</span>";
                } elseif ($_FILES["fichero"]["size"] > SIZE) {
                    echo "<span class='error'>* El tamaño del fichero no puede superar 2.5 MB.</span>";
                }
            }
            ?>
        </p>
        <button type="submit" name="btnEnviar">Contar Palabras</button>
    </form>
    <?php
    if (isset($_POST["btnEnviar"]) && !$error_form) {
        $contenido = file_get_contents($_FILES["fichero"]["tmp_name"]);
        $palabras = str_word_count($contenido);
        // Teniendo en cuenta los caracteres especiales del español
        // $palabras = str_word_count($contenido, 0, 'áéíóúüñÁÉÍÓÚÜÑ'); 


        echo "<h3>Contador</h3>";
        echo "<p>El número de palabras del archivo es $palabras</p>";
    }
    ?>
</body>

</html>