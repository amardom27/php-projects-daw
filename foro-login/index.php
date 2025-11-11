<?php
session_start();

if (isset($_SESSION["id_usuario"])) {
    echo "Logueado";
} else {
    if (isset($_POST["btnLogin"])) {
        $error_nombre = $_POST["usuario"] == "";
        $error_clave = $_POST["clave"] == "";

        $error_form = $error_nombre || $error_clave;

        if (!$error_form) {
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login BD Foro</title>
        <style>
            .error {
                color: red;
            }
        </style>
    </head>

    <body>
        <h1>Login BD Foro</h1>
        <form action="index.php" method="post">
            <p>
                <label for="usuario">Nombre: </label><br>
                <input type="text" name="usuario" id="usuario">
                <?php
                if (isset($_POST["btnLogin"]) && $error_nombre) {
                    echo "<span class='error'>* Campo obligatorio</span>";
                }
                ?>
            </p>
            <p>
                <label for="clave">Contraseña: </label><br>
                <input type="password" name="clave" id="clave">
                <?php
                if (isset($_POST["btnLogin"]) && $error_clave) {
                    echo "<span class='error'>* Campo obligatorio</span>";
                }
                ?>
            </p>
            <button type="submit" name="btnLogin">Login</button>
        </form>

    </body>

    </html>
<?php
}
?>