<?php
function error_page($title, $body) {
    return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $title . '</title>
</head>
<body>
   ' . $body . ' 
</body>
</html>';
}

function conectar_bd() {
    try {
        @$conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE_BD);
        mysqli_set_charset($conexion, "utf8");

        return $conexion;
    } catch (Exception $e) {
        session_destroy();
        die(error_page("Repaso Ex Librería", "<h1>Error en la conexion a la BD</h1><p>" . $e->getMessage() . "</p>"));
    }
}

function comprobar_login($conexion) {
    try {
        $consulta = "select id_usuario from usuarios where lector = '" . $_POST["usuario"] . "' and clave = '" . md5($_POST["clave"]) . "'";
        $res_login = mysqli_query($conexion, $consulta);

        $tupla = mysqli_fetch_assoc($res_login);
        mysqli_free_result($res_login);

        return $tupla;
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page("Repaso Ex Librería", "<h1>Error en la consulta a la BD</h1><p>" . $e->getMessage() . "</p>"));
    }
}

function comprobar_ban($conexion) {
    try {
        $consulta = "select * from usuarios where id_usuario = '" . $_SESSION["id_usuario"] . "'";
        $res_ban = mysqli_query($conexion, $consulta);

        $usuario = mysqli_fetch_assoc($res_ban);
        mysqli_free_result($res_ban);

        return $usuario;
    } catch (Exception $e) {
        session_destroy();
        mysqli_close($conexion);
        die(error_page("Repaso Ex Librería", "<h1>Error en la consulta a la BD</h1><p>" . $e->getMessage() . "</p>"));
    }
}

function comprobar_tiempo($conexion) {
    if (time() - $_SESSION["ultima_accion"] > INACTIVIDAD * 60) {
        session_unset();
        mysqli_close($conexion);

        $_SESSION["mensaje"] = "Tiempo de sesión expirado.";

        header("Location: index.php");
        exit;
    }
    $_SESSION["ultima_accion"] = time();
}
