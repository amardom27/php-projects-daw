<?php
// Siempre hay que hacer sesion_start para usar las variables de sesion
session_start();

require "../const_globales/env.php";
require "src/funciones_constantes.php";

// * Cerrar sesión
if (isset($_POST["btnSalir"])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

if (isset($_SESSION["id_usuario"])) {
    // * Control de baneo
    try {
        @$conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE_BD);
        mysqli_set_charset($conexion, "utf8");
    } catch (Exception $e) {
        // Es bueno añadir esto cuando se muere
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
        // Borrar los datos de la sesion (ya no esta logueado)
        // Es igual que hacer unset($_SESSION["id_usuario"]) por que solo tenemos una variable
        // Ya tenemos mas variables asi que no podemos hacer lo de antes
        session_unset();

        // Vamos a guardar una variable de sesion para mandar el mensaje a la vista de index cuando
        // no se ha hecho login
        $_SESSION["seguridad"] = "Usted ya no se encuentra registrado en la base de datos.";

        header("Location: index.php");
        exit;
    }

    // * Control de tiempo de inactividad
    if ((time() - $_SESSION["ultima_accion"]) > TIEMPO_INACTIVIDAD * 60) {
        // Quitamos las variables de sesion
        session_unset();
        // Mandamos el mesaje para que se recoga en la vista de login e informe al usuario
        $_SESSION["seguridad"] = "Tiempo de sesión expirado. Por favor vuelva a loguearse.";
        // Volvemos al login
        header("Location: index.php");
        exit;
    }
    // Actulizamos el tiempo
    $_SESSION["ultima_accion"] = time();

    // Se ha hecho login correctamente
    // Disponemos de una variable $conexion para consultas
    // y la variable $tupla_usu_log con los datos del usuario logueado
?>
    <h1>Primer Login</h1>
    <div>
        <p>Bienvenido <strong><?= $tupla_usu_log["nombre"] ?></strong></p>
        <form action="index.php" method="post">
            <button type="submit" name="btnSalir">Salir</button>
        </form>
    </div>
    <?php
    // Cerramos la conexion aqui por si necesitamos la conexion a la base de datos
    // en esta parte de arriba (vista de estar logueado) 
    mysqli_close($conexion)
    ?>
<?php
} else {
    if (isset($_POST["btnLogin"])) {
        $error_usuario = $_POST["usuario"] == "";
        $error_clave = $_POST["clave"] == "";

        $error_form = $error_usuario || $error_clave;

        if (!$error_form) {
            // Compruebo si el usuario esta registrado en la base de datos
            // Si esta en la base de datos inicio sesion ($_SESSION) y salto a index
            // En otro caso informo de que el usuario / contraseña no se encuentra en la base de datos
            try {
                @$conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE_BD);
                mysqli_set_charset($conexion, "utf8");
            } catch (Exception $e) {
                // Es bueno añadir esto cuando se muere
                session_destroy();
                die(error_page("Primer Login", "<p>No se ha podido conectar a la base de datos: " . $e->getMessage() . "</p>"));
            }

            try {
                $consulta = "select id_usuario from usuarios where usuario = '" . $_POST["usuario"] . "' and clave = '" . md5($_POST["clave"]) . "'";
                $resultado = mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                mysqli_close($conexion);
                session_destroy();
                die(error_page("Primer Login", "<p>No se ha podido realizar la consulta a la base de datos: " . $e->getMessage() . "</p>"));
            }
            mysqli_close($conexion);

            // Cogemos la tupla antes para poder liberar $resultado
            $tupla = mysqli_fetch_assoc($resultado);
            mysqli_free_result($resultado);

            if ($tupla) {
                // Creamos la variable de sesion -> esta registrado
                $_SESSION["id_usuario"] = $tupla["id_usuario"];
                $_SESSION["ultima_accion"] = time(); // Tiempo en segundos UNIX Epoch
                header("Location: index.php");
                exit;
            } else {
                $error_usuario = true;
            }
        }
    }
    // Pagina de inicio (puede ser una vista)
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Primer Login</title>
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
        <h1>Primer Login</h1>
        <form action="index.php" method="post">
            <p>
                <label for="usuario">Usuario: </label><br>
                <input type="text" name="usuario" id="usuario" value="<?php if (isset($_POST["usuario"])) echo $_POST["usuario"] ?>">
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
                <label for="clave">Clave: </label><br>
                <input type="password" name="clave" id="clave">
                <?php
                if (isset($_POST["btnLogin"]) && $error_clave) {
                    echo "<span class='error'>* Campo vacío.</span>";
                }
                ?>
            </p>
            <button type="submit" name="btnLogin">Login</button>
        </form>
        <?php
        if (isset($_SESSION["seguridad"])) {
            echo "<p class='mensaje'>" . $_SESSION["seguridad"] . "</p>";

            // Cuando hemos mostrado la variable quitamos las variables de sesion 
            // Es como empezar de nuevo
            session_destroy();
        }
        ?>
    </body>

    </html>
<?php
}
?>