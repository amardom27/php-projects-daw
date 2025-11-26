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

function conectar_BD() {
    try {
        @$con = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE_BD);
        mysqli_set_charset($con, "utf8");
        return $con;
    } catch (Exception $e) {
        die(error_page("Gestión de Guardias", "<h1>Error en la conexion a la BD</h1><p>" . $e->getMessage() . "</p>"));
    }
}

function comprobarUsuario($conexion) {
    try {
        $consulta = "select * from usuarios where usuario = '" . $_POST["usuario"] . "' and clave = '" . md5($_POST["clave"]) . "'";
        $res_login = mysqli_query($conexion, $consulta);

        $tupla = mysqli_fetch_assoc($res_login);
        mysqli_free_result($res_login);

        return $tupla;
    } catch (Exception $e) {
        session_destroy();
        mysqli_close($conexion);
        die(error_page("Gestión de Guardias", "<h1>Error en la consulta a la BD</h1><p>" . $e->getMessage() . "</p>"));
    }
}

function comprobarBaneo(mysqli $conexion, int|string $id_usu, string $salto = "index.php"): array|null {
    // BAN
    try {
        $consulta = "select * from usuarios where id_usuario = '" . $id_usu . "'";
        $res_ban = mysqli_query($conexion, $consulta);

        $usuario = mysqli_fetch_assoc($res_ban);
        mysqli_free_result($res_ban);
    } catch (Exception $e) {
        session_destroy();
        mysqli_close($conexion);
        die(error_page("Gestión de Guardias", "<h1>Error en la consulta a la BD</h1><p>" . $e->getMessage() . "</p>"));
    }

    if (!$usuario) {
        session_unset();
        $_SESSION["seguridad"] = "Usted ya no se encuentra en la base de datos.";

        mysqli_close($conexion);

        header("Location: " . $salto);
        exit;
    }
    return $usuario;
}

function comprobarInactividad(mysqli $conexion, string $salto = "index.php") {
    // Inactividad
    if (time() - $_SESSION["ultima_accion"] > INACTIVIDAD * 60) {
        session_unset();
        $_SESSION["seguridad"] = "Tiempo de sesión expirado. Por favor vuelva a loguearse.";

        mysqli_close($conexion);

        header("Location: " . $salto);
        exit;
    }
    $_SESSION["ultima_accion"] = time();
}
