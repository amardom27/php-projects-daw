<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 6</h1>
    <?php
    $ciudades = array("Madrid", "Barcelona", "Londres", "New York", "Los Angeles", "Chicago");

    foreach ($ciudades as $key => $value) {
        echo "<p>La ciudad con el indice $key tiene el nombre $value</p>";
    }
    ?>
</body>

</html>