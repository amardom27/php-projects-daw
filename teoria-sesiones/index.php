<?php
// Hay que iniciar la sesion para que se pueda acceder a las variables de sesion
session_start();

// Para resetear no se puede usar solamente unset($_SESSION) porque solo borrar las variables
// Tenemos que borrar el espacio reservado con el id para un dominio y navegador
if (isset($_POST["btnReset"])) {
    // Tarda en hacer el efecto hasta que se cargue la pagina, entonces se vera el $_SESSION["nombre"]
    // pero en la siguiente carga de la pagina no existira (hay que darle dos veces)
    // Usamos el unset para que el efecto sea inmediato porque borra las variables de entorno y no se muestran 
    session_unset();

    // Luego se destruyen (el sitio reservado para guardar los datos de sesion)
    session_destroy();

    // Si saltamos otra vez tenemos el efecto inmediato
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
    <h1>Teoría Sesiones</h1>
    <form action="envio.php" method="post">
        <p>
            <label for="nombre">Introduzca un nombre: </label>
            <input type="text" name="nombre" id="nombre" value="<?php if (isset($_SESSION["nombre"])) echo $_SESSION["nombre"] ?>">
        </p>
        <button type="submit" name="btnEnviar">Enviar</button>
        <!-- Formaction para cambiar donde se envia el formulario cuando se pulsa el boton -->
        <button type="submit" name="btnReset" formaction="index.php">Resetear sessión</button>
    </form>
</body>

</html>