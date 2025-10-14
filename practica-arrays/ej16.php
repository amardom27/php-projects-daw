<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 16</h1>
    <?php
    $arr = [5 => 1, 12 => 2, 13 => 56, "x" => "42"];
    print_r($arr);
    echo "<br>";
    echo "<br>";

    echo "El array tiene " . count($arr) . " elementos.";
    echo "<br>";
    echo "<br>";

    unset($arr[5]);
    print_r($arr);
    echo "<br>";
    echo "<br>";

    unset($arr);
    //print_r($arr);
    ?>
</body>

</html>