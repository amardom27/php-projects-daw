<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 5</h1>
    <?php
    $datos = array("Nombre" => "Pedro Torres", "Direccion" => "C/ Mayor, 37", "Telefono" => "123456789");

    foreach ($datos as $key => $value) {
        echo "<p>$key: $value";
    }
    ?>
</body>

</html>