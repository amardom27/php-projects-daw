<?php
require "src/functions.php";

if (isset($_POST["btnEnviar"])) {
    // Compruebo errores del formulario
    $error_foto = $_FILES["foto"]["name"] == ""
        || $_FILES["foto"]["error"] // != 0 
        || $_FILES["foto"]["size"] > 10 * 1024 * 1024 // Pasarlo a bits
        || !tiene_extension($_FILES["foto"]["name"])
        || !mi_getimagesize($_FILES["foto"]);
}

if (isset($_POST["btnEnviar"]) && !$error_foto) {
    require "vistas/vista-datos.php";
} else {
    require "vistas/vista-form.php";
}
