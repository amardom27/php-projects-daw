<?php
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
