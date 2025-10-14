<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 18</h1>
    <?php
    $deportes = array("Futbol", "Baloncesto", "Natacion", "Tenis");

    echo "<p>Total de elementos que contiene: " . count($deportes) . "</p>";

    echo "<p>Primer valor del array: " . reset($deportes) . "</p>";
    echo "<p>El siguiente valor es: " . next($deportes) . "</p>";
    echo "<p>El ultimo valor del array es: " . end($deportes) . "</p>";
    echo "<p>El anterior valor del array es: " . prev($deportes) . "</p>";
    ?>
</body>

</html>