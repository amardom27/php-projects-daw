<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 12</h1>
    <?php
    $arr1 = array("Lagartija", "Araña", "Perro", "Gato", "Ratón");
    $arr2 = array("12", "34", "45", "52", "12");
    $arr3 = array("Sauce", "Pino", "Naranjo", "Chopo", "Perro", "34");

    $juntos = [];
    foreach ($arr1 as $key => $value) {
        array_push($juntos, $value);
    }
    foreach ($arr2 as $key => $value) {
        array_push($juntos, $value);
    }
    foreach ($arr3 as $key => $value) {
        array_push($juntos, $value);
    }
    print_r($juntos);
    ?>
</body>

</html>