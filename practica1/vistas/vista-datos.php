<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 1 - Datos</title>
</head>
<body>
    <h1>Esto son los datos enviados:</h1>
    <p><strong>El nombre enviado ha sido: </strong><?php echo $_POST["name"] ?></p>
    <p><strong>Ha nacido en: </strong><?php echo $_POST["city"] ?></p>
    <!-- No hace falta comprobar el sex porque ya se comprueba con un error -->
    <p><strong>El sexo es: </strong><?php echo $_POST["sex"]; ?></p>
    <p>
        <?php 
            if(isset($_POST["aficiones"])) {
                echo "<p><strong>Las aficiones seleccionadas han sido: </strong></p>";
                echo "<ol>";
                for ($i=0; $i < count($_POST["aficiones"]); $i++) { 
                    echo "<li>" . $_POST["aficiones"][$i] . "</li>";
                } 
                echo "</ol>";
            } else {
                echo "<p><strong>No has seleccionado ninguna afición.</strong></p>";
            }
        ?>
    </p>
    <p>
        <?php
            if($_POST["comment"] == "") {
                echo "<strong>No has echo ningún comentario.</strong>";
            } else {
                echo "<strong>El comentario enviado ha sido: </strong>" . $_POST["comment"];
            }
        ?>
    </p>
</body>
</html>