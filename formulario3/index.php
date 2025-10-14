<?php
// Formulario con imagen requerida

require "src/functions_const.php";

// Resetear los campos, si no hacemos esto no se podrian borrar
// solo con un input type reset
if (isset($_POST["btnReset"])) {
    unset($_POST);
}

if (isset($_POST["btnEnviar"])) {
    // Compruebo errores del formulario
    $error_nombre = $_POST["name"] == "" || strlen($_POST["name"]) > 15;
    $error_apellido = $_POST["last"] == "" || strlen($_POST["name"]) > 40;
    $error_clave = $_POST["pass"] == "";
    $error_dni = strlen($_POST["dni"]) != 9;
    $error_sexo = !isset($_POST["sex"]);
    $error_comentario = $_POST["comment"] == "";
    $error_sub = !isset($_POST["sub"]);
    $error_foto = $_FILES["photo"]["name"] == ""
        || $_FILES["photo"]["error"] != 0
        || !tiene_extension($_FILES["photo"]["name"])
        || $_FILES["photo"]["size"] > TAM_FILE
        || !es_archivo_imagen($_FILES["photo"]);

    $error_formulario = $error_nombre || $error_apellido || $error_clave || $error_dni || $error_sexo || $error_comentario || $error_sub || $error_foto;
}

if (isset($_POST["btnEnviar"]) && !$error_formulario) {
    require "vistas/vista-recogida.php";
} else {
    require "vistas/vista-formulario.php";
}
