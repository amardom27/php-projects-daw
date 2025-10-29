<?php
const SERVIDOR_BD = "localhost";
const USUARIO_BD = "jose";
const CLAVE_BD = "josefa";
const NOMBRE_BD = "bd_cv";

const NOMBRE_NO_IMAGEN_BD = "no_imagen.jpg";

function error_page($title, $body) {
    $html = '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . $title . '</title>
    </head>
    <body>' . $body . '          
    </body>
    </html>';
    return $html;
}

function repetido($conexion, $tabla, $columna, $valor) {
    try {
        $consulta = "select " . $columna . " from " . $tabla . " where " . $columna . "='" . $valor . "'";
        $result_repetido = mysqli_query($conexion, $consulta);
        $respuesta = mysqli_num_rows($result_repetido) > 0;
        mysqli_free_result($result_repetido);
    } catch (Exception $e) {
        $respuesta = $e->getMessage();
    }


    return $respuesta;
}

function repetido_edit($conexion, $tabla, $columna, $valor, $columna_clave, $valor_clave) {
    try {
        $consulta = "select " . $columna . " from " . $tabla . " where " . $columna . "='" . $valor . "' AND " . $columna_clave . "<>'" . $valor_clave . "'";
        $result_repetido = mysqli_query($conexion, $consulta);
        $respuesta = mysqli_num_rows($result_repetido) > 0;
        mysqli_free_result($result_repetido);
    } catch (Exception $e) {
        $respuesta = $e->getMessage();
    }


    return $respuesta;
}


function LetraNIF($dni) {
    return substr("TRWAGMYFPDXBNJZSQVHLCKEO", $dni % 23, 1);
}

function dni_bien_escrito($texto) {
    $dni = strtoupper($texto);
    return strlen($dni) == 9 && is_numeric(substr($dni, 0, 8)) && substr($dni, -1) >= "A" && substr($dni, -1) <= "Z";
}

function dni_valido($texto) {
    $dni = strtoupper($texto);
    return LetraNIF(substr($dni, 0, 8)) == substr($dni, -1);
}


function tiene_extension($name) {
    $extension = false;
    $array = explode(".", $name);
    if (count($array) > 1) {
        $extension = end($array);
    }

    return $extension;
}

function mi_getimagesize($info_foto) {
    $respuesta = false;
    if ($info_foto["size"] > 0) {
        $respuesta = getimagesize($info_foto["tmp_name"]);
    }
    return $respuesta;
}
