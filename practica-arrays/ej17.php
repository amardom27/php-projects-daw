<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 17</h1>
    <?php
    $familias = array(
        "Los Simpson" => array(
            "Padre" => "Homer",
            "Madre" => "Marge",
            "Hijos" => array(
                "Hijo1" => "Bart",
                "Hijo2" => "Lisa",
                "Hijo3" => "Maggie",
            )
        ),
        "Los Griffin" => array(
            "Padre" => "Peter",
            "Madre" => "Lois",
            "Hijos" => array(
                "Hijo1" => "Chris",
                "Hijo2" => "Meg",
                "Hijo3" => "Stewie",
            )
        )
    );

    echo "<ul>";
    // Nombre de las familias
    foreach ($familias as $key => $value) {
        echo "<li>$key";
        echo "<ul>";

        // Parentesco de las familias (Padre, Madre, ...)
        foreach ($value as $key => $value) {
            if (!is_array($value)) {
                echo "<li>$key: $value</li>";
            } else {
                echo "<li>$key:";
                echo "<ul>";

                // Hijos de la Familia
                foreach ($value as $key => $value) {
                    echo "<li>$key: $value</li>";
                }
                echo "</ul>";
                echo "</li>";
            }
        }
        echo "</ul>";
        echo "</li>";
    }
    echo "</ul>";
    ?>
</body>

</html>