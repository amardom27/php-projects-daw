<?php
const NOMBRE_BD = "bd_exam_colegio2";
const INACTIVIDAD = 15;

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

function conectarBD() {
    try {
        @$con = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE_BD);
        mysqli_set_charset($con, "utf8");
        return $con;
    } catch (Exception $e) {
        die(error_page("Examen 4", "<h1>Error en la conexion a la BD</h1><p>" . $e->getMessage() . "</p>"));
    }
}

function verificar_ban($conexion, $id_usu, $redirect = "index.php") {
    try {
        $consulta = "select cod_usu, nombre from usuarios where cod_usu = '$id_usu'";
        $res_ban = mysqli_query($conexion, $consulta);

        $usuario = mysqli_fetch_assoc($res_ban);
        mysqli_free_result($res_ban);
    } catch (Exception $e) {
        session_destroy();
        mysqli_close($conexion);
        die(error_page("Examen 4", "<h1>Error en la consulta a la BD</h1><p>" . $e->getMessage() . "</p>"));
    }

    if (!$usuario) {
        session_unset();
        $_SESSION["seguridad"] = "Usted ya no se encuentra en la base de datos.";
        header("Location: $redirect");
        exit;
    }
    return $usuario;
}

function verificar_inactividad($conexion, $tiempo_max = 10, $redirect = "index.php") {
    if ((time() - $_SESSION["ultima_accion"]) > $tiempo_max * 60) {
        session_unset();
        $_SESSION["seguridad"] = "Tiempo de sesión expirado. Por favor vuelva a loguearse.";
        mysqli_close($conexion);

        header("Location: $redirect");
        exit;
    }
    $_SESSION["ultima_accion"] = time();
}

function verificar_sesion($conexion, $id_usu, $tiempo_max = 10, $redirect = "index.php") {
    // Verrificar Ban
    try {
        $consulta = "select cod_usu, nombre, usuario, tipo from usuarios where cod_usu = '$id_usu'";
        $res = mysqli_query($conexion, $consulta);
        $usuario = mysqli_fetch_assoc($res);
        mysqli_free_result($res);
    } catch (mysqli_sql_exception $e) {
        session_destroy();
        mysqli_close($conexion);
        die(error_page(
            "Examen 4",
            "<h1>Error en la consulta a la BD</h1><p>" . $e->getMessage() . "</p>"
        ));
    }

    if (!$usuario) {
        session_unset();
        $_SESSION["seguridad"] = "Usted ya no se encuentra en la base de datos.";
        mysqli_close($conexion);

        header("Location: $redirect");
        exit;
    }

    // Verificar inactividad 
    if (!isset($_SESSION["ultima_accion"])) {
        $_SESSION["ultima_accion"] = time();
    } elseif ((time() - $_SESSION["ultima_accion"]) > $tiempo_max * 60) {
        session_unset();
        $_SESSION["seguridad"] = "Tiempo de sesión expirado. Por favor vuelva a loguearse.";
        mysqli_close($conexion);

        header("Location: $redirect");
        exit;
    }

    $_SESSION["ultima_accion"] = time();

    return $usuario;
}

function obtner_notas($conexion, $id_usu) {
    try {
        $consulta = "select cod_asig, cod_usu, nota, denominacion from notas join asignaturas using (cod_asig) where notas.cod_usu = $id_usu";
        $res_notas = mysqli_query($conexion, $consulta);

        $notas = mysqli_fetch_all($res_notas, MYSQLI_ASSOC);
        mysqli_free_result($res_notas);

        return $notas;
    } catch (Exception $e) {
        session_destroy();
        mysqli_close($conexion);
        die(error_page("Examen 4", "<h1>Error en la consulta a la BD</h1><p>" . $e->getMessage() . "</p>"));
    }
}

function obtner_notas_2($conexion, $id_usu) {
    try {
        $consulta = "select asignaturas.cod_asig, asignaturas.denominacion, notas.cod_usu, notas.nota 
            from asignaturas left join notas 
                on asignaturas.cod_asig = notas.cod_asig AND notas.cod_usu = $id_usu";
        $res_notas = mysqli_query($conexion, $consulta);

        $notas = mysqli_fetch_all($res_notas, MYSQLI_ASSOC);
        mysqli_free_result($res_notas);

        return $notas;
    } catch (Exception $e) {
        session_destroy();
        mysqli_close($conexion);
        die(error_page("Examen 4", "<h1>Error en la consulta a la BD</h1><p>" . $e->getMessage() . "</p>"));
    }
}

function obtener_alumnos($conexion) {
    try {
        $consulta = "select cod_usu, nombre from usuarios where tipo = 'alumno'";
        $res_alu = mysqli_query($conexion, $consulta);

        $alumnos = mysqli_fetch_all($res_alu, MYSQLI_ASSOC);
        mysqli_free_result($res_alu);

        return $alumnos;
    } catch (Exception $e) {
        session_destroy();
        mysqli_close($conexion);
        die(error_page("Examen 4", "<h1>Error en la consulta a la BD</h1><p>" . $e->getMessage() . "</p>"));
    }
}

function obtener_asignaturas($conexion) {
}

function borrar_calificacion($conexion, $id_usu, $id_asig) {
    try {
        $consulta = "delete from notas where cod_usu = '$id_usu' and cod_asig = '$id_asig'";
        mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        session_destroy();
        mysqli_close($conexion);
        die(error_page("Examen 4", "<h1>Error en la consulta a la BD</h1><p>" . $e->getMessage() . "</p>"));
    }
}
