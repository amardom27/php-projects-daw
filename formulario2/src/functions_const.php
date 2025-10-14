<?php
const TAM_FILE = 10 * 1024 * 1024;

function tiene_extension($nombre_archivo) {
    $extension = false;
    $array = explode(".", $nombre_archivo);
    if (count($array)) {
        $extension = end($array);
    }
    return $extension;
}

function es_archivo_imagen($archivo) {
    $respuesta = false;
    if ($archivo["size"] > 0) {
        $respuesta = exif_imagetype($archivo["tmp_name"]);
    }
    return $respuesta;
}
