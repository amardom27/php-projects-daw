<?php
try {
    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    mysqli_set_charset($conexion, "utf8");
} catch (Exception $e) {
    session_destroy();
    die(error_page("Examen2 - PHP", "<h1>Examen2 - PHP</h1><p>Error no se ha podido conectar a la BD: " . $e->getMessage() . "</p>"));
}

try {
    $consulta = "select * from usuarios where id_usuario = '" . $_SESSION["id_usuario"] . "'";
    $result = mysqli_query($conexion, $consulta);
} catch (Exception $e) {
    mysqli_close($conexion);
    session_destroy();
    die(error_page("Examen2 - PHP", "<h1>Examen2 - PHP</h1><p>Error no se ha podido realizar la consulta: " . $e->getMessage() . "</p>"));
}

$usuario = mysqli_fetch_assoc($result);
mysqli_free_result($result);

if (!$usuario) {
    session_unset();
    $_SESSION["seguridad"] = "Usted ya no se encuentra en la base de datos";
    mysqli_close($conexion);

    header("Location: index.php");
    exit;
}

if (time() - $_SESSION["ultima_accion"] > TIEMPO_INACT * 60) {
    session_unset();
    $_SESSION["seguridad"] = "Tiempo de sesión expirado.";
    mysqli_close($conexion);

    header("Location: index.php");
    exit;
}
$_SESSION["ultima_accion"] = time();
