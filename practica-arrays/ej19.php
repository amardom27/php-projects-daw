<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 19</h1>
    <?php
    $amigos = array(
        "Madrid" => array(
            array(
                "Nombre" => "Pedro",
                "Edad" => 32,
                "Telefono" => "999888777",
            ),
            array(
                "Nombre" => "Lucía",
                "Edad" => 28,
                "Telefono" => "666555444",
            ),
            array(
                "Nombre" => "Javier",
                "Edad" => 36,
                "Telefono" => "111222333",
            )
        ),
        "Barcelona" => array(
            array(
                "Nombre" => "Susana",
                "Edad" => 34,
                "Telefono" => "777888999",
            ),
            array(
                "Nombre" => "Andrés",
                "Edad" => 29,
                "Telefono" => "444555666",
            ),
            array(
                "Nombre" => "María",
                "Edad" => 31,
                "Telefono" => "222111000",
            )
        ),
        "Toledo" => array(
            array(
                "Nombre" => "Sonia",
                "Edad" => 42,
                "Telefono" => "123123123",
            ),
            array(
                "Nombre" => "Raúl",
                "Edad" => 38,
                "Telefono" => "555444333",
            ),
            array(
                "Nombre" => "Elena",
                "Edad" => 27,
                "Telefono" => "999000111",
            )
        )
    );

    foreach ($amigos as $key => $value) {
        echo "<p>Amigos en $key:</p>";

        echo "<ol>";
        foreach ($value as $key => $value) {
            echo "<li>";
            foreach ($value as $key => $value) {
                echo "<strong>$key:</strong> $value. ";
            }
            echo "</li>";
        }
        echo "</ol>";
    }
    ?>
</body>

</html>