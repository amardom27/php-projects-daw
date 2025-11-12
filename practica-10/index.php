<?php
session_name("practica_10");
session_start();

if (isset($_POST["btnSalir"])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

require "src/constantes.php";

if (isset($_SESSION["id_usuario"])) {
    // ? Control de baneo
    try {
        @$conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE_DB);
        mysqli_set_charset($conexion, "utf8");
    } catch (Exception $e) {
        session_destroy();
        die(error_page("Primer Login", "<p>No se ha podido conectar a la base de datos: " . $e->getMessage() . "</p>"));
    }

    try {
        $consulta = "select * from usuarios where id_usuario = '" . $_SESSION["id_usuario"] . "'";
        $resultado_baneo = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(error_page("Primer Login", "<p>No se ha podido realizar la consulta a la base de datos: " . $e->getMessage() . "</p>"));
    }

    $tupla_usu_log = mysqli_fetch_assoc($resultado_baneo);
    mysqli_free_result($resultado_baneo);

    if (!$tupla_usu_log) {
        session_unset();
        $_SESSION["seguridad"] = "Usted ya no se encuentra registrado en la base de datos.";

        header("Location: index.php");
        exit;
    }

    // ? Control de tiempo de inactividad
    if ((time() - $_SESSION["ultima_accion"]) > TIEMPO_INACTIVIDAD * 60) {
        session_unset();
        $_SESSION["seguridad"] = "Tiempo de sesión expirado. Por favor vuelva a loguearse.";

        header("Location: index.php");
        exit;
    }
    $_SESSION["ultima_accion"] = time();

    // Control del tipo de usuario
    if ($tupla_usu_log["tipo"] == "admin") {
        mysqli_close($conexion);

        $_SESSION["id_usuario"] = $tupla["id_usuario"];
        $_SESSION["ultima_accion"] = time();

        header("Location: admin/gest_admin.php");
        exit;
    } else {
?>
        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Practica 10</title>
        </head>

        <body>
            <h1>Practica 10</h1>
            <p>Bienvenido <strong><?= $tupla_usu_log["nombre"] ?></strong></p>
            <p>Eres un usuario <strong>Normal</strong></p>
            <form action="index.php" method="post">
                <button type="submit" name="btnSalir">Salir</button>
            </form>

        </body>

        </html>
    <?php
    }
    mysqli_close($conexion);
    ?>
<?php
} else {
    if (isset($_POST["btnLogin"])) {
        $error_usuario = $_POST["usuario"] == "";
        $error_clave = $_POST["clave"] == "";

        $error_form = $error_usuario || $error_clave;

        // ? Comprobar que esta en la base de datos
        if (!$error_form) {
            try {
                @$conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE_DB);
                mysqli_set_charset($conexion, "utf8");
            } catch (Exception $e) {
                session_destroy();
                die(error_page("Login BD Foro", "<p>No se ha podido conectar a la base de datos: " . $e->getMessage() . "</p>"));
            }

            try {
                $consulta = "select * from usuarios where usuario = '" . $_POST["usuario"] . "' and clave = '" . md5($_POST["clave"]) . "'";
                $resultado = mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                session_destroy();
                mysqli_close($conexion);
                die(error_page("Login BD Foro", "<p>No se ha podido realizar la consulta base de datos: " . $e->getMessage() . "</p>"));
            }
            mysqli_close($conexion);

            $tupla = mysqli_fetch_assoc($resultado);
            mysqli_free_result($resultado);

            if ($tupla) {
                $_SESSION["id_usuario"] = $tupla["id_usuario"];
                $_SESSION["ultima_accion"] = time();

                if ($tupla["tipo"] == "admin") {
                    header("Location: admin/gest_admin.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                $error_usuario = true;
            }
        }
    }
?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Practica 10</title>
        <style>
            .error {
                color: red;
            }

            .mensaje {
                color: blue;
            }
        </style>
    </head>

    <body>
        <h1>Practica 10</h1>
        <form action="index.php" method="post">
            <p>
                <label for="usuario">Nombre: </label><br>
                <input type="text" name="usuario" id="usuario">
                <?php
                if (isset($_POST["btnLogin"]) && $error_usuario) {
                    if ($_POST["usuario"] == "") {
                        echo "<span class='error'>* Campo vacío.</span>";
                    } else {
                        echo "<span class='error'>* Credenciales inválidas.</span>";
                    }
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
        <?php
        if (isset($_SESSION["seguridad"])) {
            echo "<p class='mensaje'>" . $_SESSION["seguridad"] . "</p>";
            session_destroy();
        }
        ?>
    </body>

    </html>
<?php
}
?>