<?php
try
{
    @$conexion=mysqli_connect(SERVIDOR_BD, USUARIO_BD,CLAVE_BD,NOMBRE_BD);
    mysqli_set_charset($conexion,"utf8");
}
catch(Exception $e)
{
    session_destroy();
    die(error_page("Práctica 9", "<h1>Práctica 9</h1><p>Error no se ha podido conectar a la BD: ".$e->getMessage()."</p>"));
}

try
{
    $consulta="select * from usuarios where id_usuario='".$_SESSION["id_usuario"]."'";
    $result=mysqli_query($conexion,$consulta);
}
catch(Exception $e)
{
    mysqli_close($conexion);
    session_destroy();
    die(error_page("Práctica 9", "<h1>Práctica 9</h1><p>Error no se ha podido realizar la consulta: ".$e->getMessage()."</p>"));
}

$datos_usu_log=mysqli_fetch_assoc($result);
mysqli_free_result($result);

if(!$datos_usu_log)
{
    session_unset();
    $_SESSION["seguridad"]="Usted ya no se encuentra registrado en la BD";
    header("Location:".$salto);
    exit;

}
// Acabo de pasar el control de baneo y
// Ahora voy a pasar o no el control de inactividad

if(time()-$_SESSION["ultm_accion"]>TIEMPO_INACT*60)
{
    session_unset();
    $_SESSION["seguridad"]="Tiempo de sesión expirado. Por favor vuelva a loguearse";
    header("Location:".$salto);
    exit;
}

$_SESSION["ultm_accion"]=time();

?>