<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 1 - Form</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Esta es mi super página</h1>
    <form action="index.php" method="post">
        <p>
            <label for="name">Nombre: </label>
                <input type="text" name="name" id="name" value="<?php if(isset($_POST["name"])) echo $_POST["name"]; ?>">
            <?php 
                if(isset($_POST["btnEnviar"]) && $error_name) {
                    echo "<span class='error'>* Campo obligatorio *</span>";
                } 
            ?>
        </p>
        <p>
            <label for="city">Nacido en: </label>
            <select name="city" id="city">
                <option value="Sevilla"<?php if(isset($_POST["btnEnviar"]) && $_POST["city"] == "Sevilla") echo "selected"; ?>>Sevilla</option>
                <option value="Malaga" <?php if(!isset($_POST["btnEnviar"]) || ($_POST["city"] == "Malaga")) echo "selected" ?>>Málaga</option>
                <option value="Granada"<?php if(isset($_POST["btnEnviar"]) && $_POST["city"] == "Granada") echo "selected"; ?>>Granada</option>
            </select>
        </p>
        <p>
            <label for="">Sexo: </label>
            <label for="men">Hombre</label>
            <input type="radio" name="sex" id="men" value="Hombre" <?php if(isset($_POST["sex"]) && $_POST["sex"] == "men") echo "checked"; ?>>
            <label for="woman">Mujer</label>
            <input type="radio" name="sex" id="woman" value="Mujer" <?php if(isset($_POST["sex"]) && $_POST["sex"] == "woman") echo "checked"; ?>>
            <?php 
                if(isset($_POST["btnEnviar"]) && $error_sex) {
                    echo "<span class='error'>* Campo obligatorio *</span>";
                } 
            ?>
        </p>
        <p>
            <label for="">Aficiones: </label>
            <label for="sports">Deportes</label>
            <input type="checkbox" name="aficiones[]" id="sports" value="Deportes" <?php if(isset($_POST["aficiones"]) && mi_in_array("Deportes", $_POST["aficiones"])) echo "checked"; ?>>
            <label for="read">Lectura</label>
            <input type="checkbox" name="aficiones[]" id="read" value="Lectura" <?php if(isset($_POST["aficiones"]) && mi_in_array("Lectura", $_POST["aficiones"])) echo "checked"; ?>>
            <label for="other">Otros</label>
            <input type="checkbox" name="aficiones[]" id="other" value="Otros" <?php if(isset($_POST["aficiones"]) && mi_in_array("Otros", $_POST["aficiones"])) echo "checked"; ?>>
        </p>
        <p>
            <label for="comment">Comentarios: </label>
            <textarea name="comment" id="comment" rows="4" cols="30"><?php if(isset($_POST["comment"])) echo $_POST["comment"]; ?></textarea>
        </p>
        <p>
            <button type="submit" name="btnEnviar">Enviar</button>
        </p>
    </form>
</body>
</html>