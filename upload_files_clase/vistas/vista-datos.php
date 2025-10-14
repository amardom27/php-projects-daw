<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Files</title>
    <style>
        img {
            height: 500px;
        }
    </style>
</head>

<body>
    <h1>Sube un archivo al servidor</h1>
    <p>Sin errores enviando la imagen.</p>
    <?php
    $numero_unico = md5(uniqid(uniqid(), true));
    $ext = tiene_extension($_FILES["foto"]["name"]);
    $nombre_img = $numero_unico . "." . $ext;

    // @ Avisar de que podria dar error
    @$var = move_uploaded_file($_FILES["foto"]["tmp_name"], "images/" . $nombre_img);

    if (!$var) {
        echo "<p>La imagen no se ha podido mover a la carpeta destino.</p>";
    } else {
        echo "<p><strong>Nombre original: </strong>" . $_FILES["foto"]["name"] . "</p>";
        echo "<p><strong>Tipo: </strong>" . $_FILES["foto"]["type"] . "</p>";
        echo "<p><strong>Tamaño: </strong>" . $_FILES["foto"]["size"] . "</p>";
        echo "<p><strong>Archivo temporal: </strong>" . $_FILES["foto"]["tmp_name"] . "</p>";
        echo "<p><img src='images/" . $nombre_img . "' alt='Imagen subida'</p>";
    }
    ?>
</body>

</html>