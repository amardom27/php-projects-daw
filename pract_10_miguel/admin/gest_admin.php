<?php
session_name("Pract_10_25_26");
session_start();

require "../src/funciones_ctes.php";

if(isset($_SESSION["id_usuario"]))
{
    $salto="../index.php";
    require "../src/control_seguridad.php";

    if($datos_usu_log["tipo"]=="normal")
    {
        mysqli_close($conexion);
        header("Location:".$salto);
        exit;
    }
    else
        require "../vistas/vista_admin.php";


    mysqli_close($conexion);

}
else
{
    header("Location:../index.php");
    exit;
}
?>