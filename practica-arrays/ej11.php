<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 11</h1>
    <?php
    $arr1 = array("Lagartija", "Araña", "Perro", "Gato", "Ratón");
    $arr2 = array("12", "34", "45", "52", "12");
    $arr3 = array("Sauce", "Pino", "Naranjo", "Chopo", "Perro", "34");

    $juntos = array_merge($arr1, $arr2, $arr3);

    print_r($juntos);
    ?>
</body>

</html>