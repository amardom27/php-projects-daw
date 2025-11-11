<?php
function error_page($title, $body) {
    $html = '<!DOCTYPE html>
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
    return $html;
}

// Funcion para calcular la letra del DNI
function LetraNIF($dni) {
    $valor = (int) ($dni / 23);
    $valor *= 23;
    $valor = $dni - $valor;
    $letras = "TRWAGMYFPDXBNJZSQVHLCKEO";
    $letraNif = substr($letras, $valor, 1);
    return $letraNif;
}

// Funcion para saber si un valor esta repetido en la base de datos
function repetido($conexion, $tabla, $columna, $valor) {
    try {
        $consulta = "select " . $columna . " from " . $tabla . " where " . $columna . " = '" . $valor . "'";
        $resultado_repetido = mysqli_query($conexion, $consulta);
        $respuesta = mysqli_num_rows($resultado_repetido) > 0;

        // No olvidar liberar el resultado
        mysqli_free_result($resultado_repetido);
    } catch (Exception $e) {
        $respuesta = $e->getMessage();
    }
    return $respuesta;
}

// Funcion para saber si un valor esta repetido en la base de datos cuando pulsamos editar
// Necesitamos la clave porque si miramos como antes siempre nos sale un usuario que es el mismo
function repetido_editar($conexion, $tabla, $columna, $valor, $columna_id, $valor_id) {
    try {
        $consulta = "select " . $columna . " from " . $tabla . " where " . $columna . " = '" . $valor . "' and " . $columna_id . " <> " . $valor_id;
        $resultado_repetido = mysqli_query($conexion, $consulta);
        $respuesta = mysqli_num_rows($resultado_repetido) > 0;

        // No olvidar liberar el resultado
        mysqli_free_result($resultado_repetido);
    } catch (Exception $e) {
        $respuesta = $e->getMessage();
    }
    return $respuesta;
}

function dni_bien_escrito($dni) {
    $dni = strtoupper($dni);
    return strlen($dni) == 9 && is_numeric(substr($dni, 0, 8)) && (substr($dni, -1) >= 'A' && substr($dni, -1) < 'Z');
}

function dni_valido($dni) {
    $dni = strtoupper($dni);
    return LetraNIF(substr($dni, 0, 8)) == substr($dni, -1);
}

function tiene_extension($name) {
    $extension = false;
    $arr = explode(".", $name);

    if (count($arr) > 1) {
        $extension = end($arr);
    }
    return $extension;
}

function mi_getimagesize($info_foto) {
    // Contorlar un archivo vacio con extension de imagen
    return $info_foto["size"] > 0 ? getimagesize($info_foto["tmp_name"]) : false;
}
