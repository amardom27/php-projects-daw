<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 15</h1>
    <?php
    $numeros = array(3, 2, 8, 123, 5, 1);

    sort($numeros, SORT_NUMERIC);

    print_r($numeros);
    ?>
</body>

</html>