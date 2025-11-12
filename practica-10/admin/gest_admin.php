<?php
session_name("practica_10");
session_start();

// Si intentas entrar escribiendo la url
// if (!isset($_SESSION["admin"])) {
//     header("Location: ../index.php");
//     exit;
// }

require "../src/constantes.php";

// Controles
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

        header("Location: ../index.php");
        exit;
    }

    // ? Control de tiempo de inactividad
    if ((time() - $_SESSION["ultima_accion"]) > TIEMPO_INACTIVIDAD * 60) {
        session_unset();
        $_SESSION["seguridad"] = "Tiempo de sesión expirado. Por favor vuelva a loguearse.";

        header("Location: ../index.php");
        exit;
    }
    $_SESSION["ultima_accion"] = time();

    if ($tupla_usu_log["tipo"] != "admin") {
        mysqli_close($conexion);

        header("Location: ../index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practica 10</title>
</head>

<body>
    <h3>Hola soy administrador</h3>
    <p>Nombre: <?= $tupla_usu_log["nombre"] ?></p>
    <form action="../index.php" method="post">
        <button type="submit" name="btnSalir">Salir</button>
    </form>
</body>

</html>