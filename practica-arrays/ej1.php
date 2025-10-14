<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 1</h1>
    <?php
    const NUM = 20;

    echo "<h3>Los " . NUM . " numeros pares</h3>";
    for ($i = 0; $i < NUM; $i++) {
        if ($i % 2 == 0) {
            echo "<p>" . $i . "</p>";
        }
    }
    ?>
</body>

</html>