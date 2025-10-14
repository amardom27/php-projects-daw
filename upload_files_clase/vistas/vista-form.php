<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Files</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <h1>Sube un archivo al servidor</h1>
    <form action="index-required.php" method="post" enctype="multipart/form-data">
        <label for="foto">Seleccione una imagen con extension (Max: 10 MB): </label>
        <input type="file" name="foto" id="foto" accept="image/*">
        <?php
        if (isset($_POST["btnEnviar"]) && $error_foto) {
            if ($_FILES["foto"]["name"] == "") {
                echo "<span class='error'>* Debes seleccinar un fichero. *";
            } elseif ($_FILES["foto"]["error"]) {
                echo "<span class='error'>* No se ha subido el fichero seleccionado al servidor. *";
            } elseif (!tiene_extension($_FILES["foto"]["name"])) {
                echo "<span class='error'>* El fichero seleccinado no tiene extensión. *";
            } elseif (!mi_getimagesize($_FILES["foto"])) {
                echo "<span class='error'>* El archivo seleccionado no es un archivo imagen. *";
            } else {
                echo "<span class='error'>* El archivo seleccionado supera los 500 KB. *";
            }
        }
        ?>
        <p>
            <button type="submit" name="btnEnviar">Enviar</button>
        </p>
    </form>

</body>

</html>