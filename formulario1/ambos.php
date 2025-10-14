<?php 
if (isset($_POST["btnEnviar"])) {
    // Ponemos la vista recogida
?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recogida datos</title>
    </head>
    <body>
        <h1>Recodiga de datos</h1> 
        <p><strong>Nombre: </strong><?php echo $_POST["name"] ?></p>
        <p><strong>Apellido: </strong><?php echo $_POST["last"] ?></p>
        <p><strong>DNI: </strong><?php echo $_POST["dni"] ?></p>
        <p><strong>Sexo: </strong><?php if (isset($_POST["sex"])) echo $_POST["sex"] ?></p>
        <p><strong>Ciudad: </strong><?php echo $_POST["city"] ?></p>
        <p><strong>Comentarios: </strong><?php echo $_POST["comment"] ?></p>
        <p><strong>Suscrito: </strong><?php echo (isset($_POST["sub"])) ? "Si" : "No" ?></p>
    </body>
    </html>
<?php
} else {
    // Ponemos la vista del formulario
?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Formulario</title>
    </head>
    <body>
       <h2>Rellena tu CV</h2>
        <form action="index.php" method="post" enctype="multipart/form-data">
            <label for="name">Nombre</label>
            <br>
            <input type="text" name="name" id="name" maxlength="20" placeholder="Pepe, Juan, ...">
            <br>

            <label for="last">Apellidos</label>
            <br>
            <input type="text" name="last" id="last" size="40">
            <br>

            <label for="pass">Contraseña</label>
            <br>
            <input type="password" name="pass" id="pass">
            <br>

            <label for="dni">DNI</label>
            <br>
            <input type="text" name="dni" id="dni">
            <br>

            <label for="">Sexo</label>
            <br>
            <input type="radio" name="sex" id="men" value="men">
            <label for="men">Hombre</label>
            <br>
            <input type="radio" name="sex" id="woman" value="woman">
            <label for="woman">Mujer</label>
            <br>
            <br>

            <label for="photo">Incluir mi foto:</label>
            <input type="file" name="photo" id="photo" accept="image/*">
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
            <textarea name="comment" id="comment" rows="8" cols="40"></textarea>
            <br>
            <br>

            <input type="checkbox" name="sub" id="sub" checked>
            <label for="sub">Subscribirse al boletín de Novedades</label>
            <br>
            <br>

            <input type="submit" value="Guardar Cambios" name="btnEnviar">
            <input type="reset" value="Borrar los datos introducidos">
        </form>
    </body>
    </html>
<?php
}
?>
