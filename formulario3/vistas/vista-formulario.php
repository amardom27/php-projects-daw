<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario 2</title>
    <!-- Formulario 3 de Miguel Angel -->
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <h2>Rellena tu CV</h2>
    <form action="index.php" method="post" enctype="multipart/form-data">
        <label for="name">Nombre</label>
        <br>
        <input type="text" name="name" id="name" maxlength="15" placeholder="Pepe, Juan, ..." value="<?php if (isset($_POST["name"])) echo $_POST["name"]; ?>">
        <?php
        if (isset($_POST["btnEnviar"]) && $error_nombre) {
            if ($_POST["name"] == "") {
                echo "<span class='error'>* Error en el nombre.</span>";
            } else {
                echo "<span class='error'>* Has tecleado más de 15 caracteres.</span>";
            }
        }
        ?>
        <br>

        <label for="last">Apellidos</label>
        <br>
        <input type="text" name="last" id="last" size="40" maxlength="40" value="<?php if (isset($_POST["last"])) echo $_POST["last"]; ?>">
        <?php
        if (isset($_POST["btnEnviar"]) && $error_apellido) {
            if ($_POST["last"] == "") {
                echo "<span class='error'>* Error en el apellido.</span>";
            } else {
                echo "<span class='error'>* Has tecleado más de 40 caracteres.</span>";
            }
        }
        ?>
        <br>

        <label for="pass">Contraseña</label>
        <br>
        <input type="password" name="pass" id="pass">
        <?php
        if (isset($_POST["btnEnviar"]) && $error_clave) {
            echo "<span class='error'>* Error en la contraseña.</span>";
        }
        ?>
        <br>

        <label for="dni">DNI</label>
        <br>
        <input type="text" name="dni" id="dni" value="<?php if (isset($_POST["dni"])) echo $_POST["dni"]; ?>">
        <?php
        if (isset($_POST["btnEnviar"]) && $error_dni) {
            echo "<span class='error'>* No has puesto 9 caracteres.</span>";
        }
        ?>
        <br>

        <label for="">Sexo</label>
        <br>
        <input type="radio" name="sex" id="men" value="hombre" <?php if (isset($_POST["sex"]) && $_POST["sex"] == "hombre") echo "checked"; ?>>
        <label for="men">Hombre</label>
        <br>
        <input type="radio" name="sex" id="woman" value="mujer" <?php if (isset($_POST["sex"]) && $_POST["sex"] == "mujer") echo "checked"; ?>>
        <label for="woman">Mujer</label>
        <?php
        if (isset($_POST["btnEnviar"]) && $error_sexo) {
            echo "<span class='error'>* Debe seleccionar un sexo.</span>";
        }
        ?>
        <br>
        <br>

        <!-- No mantener las fotos por privacidad -->
        <label for="photo">Incluir mi foto (Max: 10MB):</label>
        <input type="file" name="photo" id="photo" accept="image/*">
        <?php
        if (isset($_POST["btnEnviar"]) && $error_foto) {
            if ($_FILES["photo"]["name"] == "") {
                echo "<span class='error'>* Campo requerido.</span>";
            } elseif ($_FILES["photo"]["error"]) {
                echo "<span class='error'>* Error en la subida de la imagen seleccionada.</span>";
            } elseif (!tiene_extension($_FILES["photo"]["name"])) {
                echo "<span class='error'>* El archivo seleccionado no tiene extensión.</span>";
            } elseif ($_FILES["photo"]["size"] > TAM_FILE) {
                echo "<span class='error'>* El tamaño del archivo supera los 10MB.</span>";
            } else {
                echo "<span class='error'>* El archivo seleccionado no es un archivo imagen.</span>";
            }
        }
        ?>
        <br>
        <br>

        <label for="city">Nacido en:</label>
        <select name="city" id="city">
            <option value="malaga" selected>Málaga</option>
            <option value="granada">Granada</option>
            <option value="cadiz">Cádiz</option>
        </select>
        <br>
        <br>

        <label for="comment">Comentarios:</label>
        <textarea name="comment" id="comment" rows="8" cols="40"><?php if (isset($_POST["comment"])) echo $_POST["comment"] ?></textarea>
        <?php
        if (isset($_POST["btnEnviar"]) && $error_comentario) {
            echo "<span class='error'>* Error en el comentario.</span>";
        }
        ?>
        <br>
        <br>

        <input type="checkbox" name="sub" id="sub" checked>
        <label for="sub">Subscribirse al boletín de Novedades</label>
        <?php
        if (isset($_POST["btnEnviar"]) && $error_sub) {
            echo "<span class='error'>* Debe marcar la subscripción.</span>";
        }
        ?>
        <br>
        <br>

        <input type="submit" value="Guardar Cambios" name="btnEnviar">
        <input type="submit" value="Borrar los datos introducidos" name="btnReset">
    </form>
</body>

</html>