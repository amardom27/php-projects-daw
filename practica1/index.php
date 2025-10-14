<?php
require "src/func-const.php";

//echo "<h1>" . PI . " - " . PO . "</h1>";

if (isset($_POST["btnEnviar"])) {
    // Comprobamos errores del formulario
    $error_name = $_POST["name"] == "";
    $error_sex = !isset($_POST["sex"]);

    $error_form = $error_name || $error_sex;
}

if (isset($_POST["btnEnviar"]) && !$error_form) {
    // Vista recogida de datos
    require "vistas/vista-datos.php";
} else {
    // Vista formulario
    require "vistas/vista-form.php";
}
