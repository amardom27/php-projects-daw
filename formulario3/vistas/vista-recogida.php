<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recogida datos</title>
    <style>
        img {
            height: 400px;
        }
    </style>
</head>

<body>
    <h1>Recodiga de datos</h1>
    <p><strong>Nombre: </strong><?php echo $_POST["name"] ?></p>
    <p><strong>Apellido: </strong><?php echo $_POST["last"] ?></p>
    <p><strong>DNI: </strong><?php echo $_POST["dni"] ?></p>
    <p><strong>Sexo: </strong><?php if (isset($_POST["sex"])) echo $_POST["sex"] ?></p>
    <p><strong>Ciudad: </strong><?php echo $_POST["city"] ?></p>
    <p><strong>Comentarios: </strong><?php echo $_POST["comment"] ?></p>
    <p><strong>Suscrito: </strong><?php echo (isset($_POST["sub"])) ? "Si" : "No" ?></p>
    <?php
    if ($_FILES["photo"]["name"] == "") {
        echo "<p><strong>Foto: </strong>No has seleccionado una imagen";
    } else {
        $numero_unico = md5(uniqid(uniqid(), true));
        $ext = tiene_extension($_FILES["photo"]["name"]);
        $nombre_img = $numero_unico . "." . $ext;

        // @ Avisar de que podria dar error
        @$var = move_uploaded_file($_FILES["photo"]["tmp_name"], "images/" . $nombre_img);

        if (!$var) {
            echo "<p><strong>Foto: </strong>La imagen no se ha podido mover a la carpeta destino.</p>";
        } else {
            echo "<p><strong>Foto: </strong>";
            echo "<p><strong>Nombre original: </strong>" . $_FILES["photo"]["name"] . "</p>";
            echo "<p><strong>Tipo: </strong>" . $_FILES["photo"]["type"] . "</p>";
            echo "<p><strong>Tamaño: </strong>" . $_FILES["photo"]["size"] . "</p>";
            echo "<p><strong>Archivo temporal: </strong>" . $_FILES["photo"]["tmp_name"] . "</p>";
            echo "<p><img src='images/" . $nombre_img . "' alt='Imagen subida'</p>";
        }
    }
    ?>
</body>

</html>