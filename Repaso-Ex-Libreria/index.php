<?php
session_name("Repaso-Ex-Libreria");
session_start();

require "../const_globales/env.php";
const NOMBRE_BD = "bd_libreria_exam";
const INACTIVIDAD = 10;

require "./src/func_ctes.php";

if (isset($_POST["btnSalir"])) {
    session_destroy();

    header("Location: index.php");
    exit;
}

$conexion = conectar_bd();

if (isset($_SESSION["id_usuario"])) {
    $usuario = comprobar_ban($conexion);
    comprobar_tiempo($conexion);

    if ($usuario["tipo"] == "admin") {
        mysqli_close($conexion);

        header("Location: admin/libros.php");
        exit;
    }
} else {
    if (isset($_POST["btnLogin"])) {
        $error_usuario = $_POST["usuario"] == "";
        $error_clave = $_POST["clave"] == "";

        $error_form = $error_usuario | $error_clave;

        if (!$error_form) {
            $tupla = comprobar_login($conexion);

            if ($tupla) {
                $_SESSION["id_usuario"] = $tupla["id_usuario"];
                $_SESSION["ultima_accion"] = time();

                if ($tupla["tipo"] == "admin") {
                    header("Location: admin/libros.php");
                    exit;
                }
                header("Location: index.php");
                exit;
            } else {
                $error_usuario = true;
            }
        }
    }
}
mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repaso Ex Librería</title>
    <style>
        input#usuario {
            margin-bottom: 8px;
        }

        label {
            display: inline-block;
            width: 90px;
        }

        button {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_SESSION["id_usuario"])) {
    ?>
        <h1>Repaso Librería</h1>
        <form action="index.php" method="post">
            <p>Bienvenido <strong><?= $usuario["lector"] ?></strong> - <button type="submit" name="btnSalir">Salir</button></p>
        </form>
    <?php
    } else {
    ?>
        <h1>Repaso Librería</h1>
        <form action="index.php" method="post">
            <label for="usuario">Usuario: </label>
            <input type="text" name="usuario" id="usuario">
            <?php
            if (isset($_POST["btnLogin"]) && $error_usuario) {
                if ($_POST["usuario"] == "") {
                    echo "<span>* Campo obligatorio.</span>";
                } else {
                    echo "<span>* Credenciales inválidas.</span>";
                }
            }
            ?>
            <br>
            <label for="clave">Contraseña: </label>
            <input type="password" name="clave" id="clave">
            <?php
            if (isset($_POST["btnLogin"]) && $error_clave) {
                echo "<span>* Campo obligatorio.</span>";
            }
            ?>
            <p>
                <button type="submit" name="btnLogin">Login</button>
            </p>
        </form>
    <?php
    }
    ?>
</body>

</html>