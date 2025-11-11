<?php
// Iniciar la sesion, para que guarde un id especifico para el dominio y el navegador
session_start();

// Controlar que solo entre aqui desde el boton, no si se escribe en la url
if (!isset($_POST["btnEnviar"])) {
    // Redirigir a index
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teoría sesiones</title>
</head>

<body>
    <h1>Datos recibidos</h1>
    <?php
    if ($_POST["nombre"] == "") {
        echo "<p>No se ha enviado nada en el campo nombre.</p>";
    } else {
        echo "<p>El nombre enviado es: <strong>" . $_POST["nombre"] . "</strong></p>";
    }

    // Guardar en nombre dentro de la informacion de sesion
    $_SESSION["nombre"] = $_POST["nombre"];
    ?>
</body>

</html>