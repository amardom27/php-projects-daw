<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 7</h1>
    <?php
    $ciudades = array("MD" => "Madrid", "BCN" => "Barcelona", "LN" => "Londres", "NY" => "New York", "LA" => "Los Angeles", "CH" => "Chicago");

    foreach ($ciudades as $key => $value) {
        echo "<p>El indice del array que contiene como valor $value es $key";
    }
    ?>
</body>

</html>