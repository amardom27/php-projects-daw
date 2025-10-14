<?php
require "src/functions.php";

if (isset($_POST["btnEnviar"])) {
    // Compruebo errores del formulario
    $error_foto = $_FILES["foto"]["name"] != "" &&
        (
            $_FILES["foto"]["error"] // != 0 
            || $_FILES["foto"]["size"] > 10 * 1024 * 1024 // Pasarlo a bits
            || !tiene_extension($_FILES["foto"]["name"])
            || !getimagesize($_FILES["foto"]["tmp_name"])
        );
}

// $_FILES["foto"]["name"] != "" para comprobar que no se ha enviado y nos quedamos en el formulario
if (isset($_POST["btnEnviar"]) && $_FILES["foto"]["name"] != "" && !$error_foto) {
    require "vistas/vista-datos-optional.php";
} else {
    require "vistas/vista-form-optional.php";
}
