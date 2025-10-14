<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 10</h1>
    <?php
    $enteros = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
    $sum = 0;
    $cont = 0;

    foreach ($enteros as $key => $value) {
        if ($key % 2 !== 0) {
            $sum += $value;
            $cont++;
        } else {
            echo "<p>$value</p>";
        }
    }
    $media = $sum / $cont;
    echo "La media es de las posiciones pares es: $media";
    ?>
</body>

</html>