<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 8</h1>
    <?php
    $nombres = array("Pedro", "Ismael", "Sonia", "Clara", "Susana", "Alfonso", "Teresa");

    echo "<p>El array contiene " . count($nombres) . " elementos.</p>";

    echo "<h3>Nombres</h3>";
    echo "<ul>";
    foreach ($nombres as $key => $value) {
        echo "<li>$value</li>";
    }
    echo "</ul>"
    ?>
</body>

</html>