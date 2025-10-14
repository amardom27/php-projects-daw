<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejemplo de Formulario</title>
    <style>
        .error{color:red}
    </style>
</head>
<body>
    <h1>Rellena tu CV</h1>
    <form method="post" action="index.php" enctype="multipart/form-data">
    <label for="nombre">Nombre</label><br>
    <input type="text" id="nombre" name="nombre" maxlength="15" placeholder="Introduzca un nombre" value="<?php if(isset($_POST["nombre"])) echo $_POST["nombre"];?>">
    <?php
    if(isset($_POST["btnEnviar"]) && $error_nombre)
    {
        if($_POST["nombre"]=="")
            echo "<span class='error'>* Campo Obligatorio *</span>";
        else 
            echo "<span class='error'>* Has tecleado más de 15 carácteres *</span>";
    }    
    ?>
    <br>
        <label for="apellidos">Apellidos</label><br>
    <input type="text" id="apellidos" name="apellidos" value="<?php if(isset($_POST["apellidos"])) echo $_POST["apellidos"];?>" maxlength="40" placeholder="Introduzca los apellidos" size="50">
        <?php
    if(isset($_POST["btnEnviar"]) && $error_apellidos)
    {
        if($_POST["apellidos"]=="")
            echo "<span class='error'>* Campo Obligatorio *</span>";
        else 
            echo "<span class='error'>* Has tecleado más de 40 carácteres *</span>";
    }    
    ?>
    <br>
    <label for="clave">Contraseña</label><br>
    <input type="password" id="clave" name="clave" maxlength="15" placeholder="Introduzca una contraseña">
        <?php
    if(isset($_POST["btnEnviar"]) && $error_clave)
    {
        if($_POST["clave"]=="")
            echo "<span class='error'>* Campo Obligatorio *</span>";
        else 
            echo "<span class='error'>* Has tecleado más de 15 carácteres *</span>";
    }    
    ?>
    
    <br>
        <label for="dni">DNI</label><br>
    <input type="text" id="dni" name="dni" maxlength="9" placeholder="Introduzca un DNI" value="<?php if(isset($_POST["dni"])) echo $_POST["dni"];?>">
        <?php
    if(isset($_POST["btnEnviar"]) && $error_dni)
    {
        echo "<span class='error'>* No has tecleado 9 carácteres *</span>";
    }    
    ?>
    <br>
        <label>Sexo</label><br>
        <input type="radio" name="sexo" id="hombre" value="hombre" <?php if(isset($_POST["sexo"]) && $_POST["sexo"]=="hombre") echo "checked";?>> <label for="hombre">Hombre</label><br>
        <input type="radio" name="sexo" id="mujer" value="mujer" <?php if(isset($_POST["sexo"]) && $_POST["sexo"]=="mujer") echo "checked";?>> <label for="mujer">Mujer</label>
            <?php
        if(isset($_POST["btnEnviar"]) && $error_sexo)
        {
            echo "<span class='error'>* Debes seleccionar un sexo *</span>";
        }    
        ?>

        <br>
        <br><label for="foto">Incluir mi foto</label><input type="file" name="foto" id="foto" accept="image/*"><br>
        <br><label for="nacido">Nacido en: </label>
        <select name="nacido" id="nacido">
            <option value="Granada">Granada</option>
            <option value="Málaga" selected>Málaga</option>
            <option value="Almería">Almería</option>
        </select>
        <br>

        <br>
        <label for="comentarios">Comentarios: </label>
        <textarea id="comentarios" name="comentarios" cols="40" rows="5" placeholder="Introduzca un comentario"><?php if(isset($_POST["comentarios"])) echo $_POST["comentarios"];?></textarea>
        <?php
        if(isset($_POST["btnEnviar"]) && $error_comentarios)
        {
            echo "<span class='error'>* Campo Obligatorio *</span>";
        }    
        ?>
        <br>
        <br>
        <input type="checkbox" name="subs" id="subs" checked><label for="subs">Subscribirse al boletín de Novedades</label>
            <?php
        if(isset($_POST["btnEnviar"]) && $error_subs)
        {
            echo "<span class='error'>* Debes marcar la subscripción *</span>";
        }    
        ?>
        <br>
    <br><input type="submit" value="Guardar Cambios" name="btnEnviar"> 
    <input type="reset" value="Borrar los datos introducidos">
    </form>
</body>
</html>