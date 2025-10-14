<?php
if(isset($_POST["btnEnviar"]))
{
    //Compruebo errores del formulario
    $error_nombre=$_POST["nombre"]=="" || strlen($_POST["nombre"])>15;
    $error_apellidos=$_POST["apellidos"]=="" || strlen($_POST["apellidos"])>40;
    $error_clave=$_POST["clave"]=="" || strlen($_POST["clave"])>15;
    $error_dni=strlen($_POST["dni"])!=9;
    $error_sexo=!isset($_POST["sexo"]);
    $error_comentarios=$_POST["comentarios"]=="";
    $error_subs=!isset($_POST["subs"]);

    $error_formulario=$error_nombre||$error_apellidos||$error_clave||$error_dni||$error_sexo||$error_comentarios||$error_subs;

    
}

if(isset($_POST["btnEnviar"]) && !$error_formulario)
{
    require "vistas/vista_recogida.php";
}
else
{
    require "vistas/vista_formulario.php";
}
?>