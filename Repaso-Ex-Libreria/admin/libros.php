<?php
session_name("Repaso-Ex-Libreria");
session_start();

require "../../const_globales/env.php";
const NOMBRE_BD = "bd_libreria_exam";
const INACTIVIDAD = 10;

require "../src/func_ctes.php";


if (isset($_SESSION["id_usuario"])) {
    $conexion = conectar_bd();

    $usuario = comprobar_ban($conexion);
    comprobar_tiempo($conexion);

    if ($usuario["tipo"] == "normal") {
        mysqli_close($conexion);

        header("Location: ../index.php");
        exit;
    }

    mysqli_close($conexion);
?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Repaso Ex Librería</title>
    </head>

    <body>
        <h1>Repaso Librería</h1>
        <form action="../index.php" method="post">
            <p>Bienvenido <strong><?= $usuario["lector"] ?></strong> - <button type="submit" name="btnSalir">Salir</button></p>
        </form>
    </body>

    </html>
<?php
} else {
    header("Locaition: ../index.php");
    exit;
}
