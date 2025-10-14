<?php
if (!isset($_POST["btnEnviar"])) {
    header("Location:index.php");
    exit;
}
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