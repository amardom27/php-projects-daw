<?php
require "../../../const_globales/env.php";
const NOMBRE_BD = "bd_libreria_exam";
const TIEMPO_INACTIVIDAD = 10; // minutos

if (isset($_SESSION["id_usuario"])):

else:
    session_destroy();

    header("Location: ../index.php");
    exit;
endif;
