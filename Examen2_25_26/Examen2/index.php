<?php
session_name("Examen2_25_26");
session_start();

require "src/funciones_ctes.php";

//Aquí pondría el código para cerra la Sesión
if (isset($_POST["btnSalir"])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

if (isset($_SESSION["id_usuario"])) {
    $salto = "index.php";
    require "src/control_seguridad.php";

    // Acabo de pasar el control de seguridad y ahora tengo que cargar las vistas oportunas
    if ($usuario["tipo"] == "admin") {
        require "vistas/vista_admin.php";
    } else {
        require "vistas/vista_normal.php";
    }
} else {
    //NO estoy logueado y cargo la vista home
    require "vistas/vista_home.php";
}
