<?php
if(isset($_POST["btnEnviar"])) {
    // Copia el codigo de el otro archivo aqui
    require "vistas/vista-recogida.php";
} else {
    require "vistas/vista-formulario.php";
}
?>