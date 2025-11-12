<?php
if(isset($_POST["btnLogin"]))
{
    $error_usuario=$_POST["usuario"]=="";
    $error_clave=$_POST["clave"]=="";
    $error_form=$error_usuario||$error_clave;
    if(!$error_form)
    {
        //Compruebo usuario registrado en la BD
        //Si esta en la BD lo logueo y salto a index.php
        //en otro caso informo de que el usuario/contraseña no se encuentra en la BD

        try
        {
            @$conexion=mysqli_connect(SERVIDOR_BD, USUARIO_BD,CLAVE_BD,NOMBRE_BD);
            mysqli_set_charset($conexion,"utf8");
        }
        catch(Exception $e)
        {
            session_destroy();
            die(error_page("Práctica 10", "<h1>Práctica 10</h1><p>Error no se ha podido conectar a la BD: ".$e->getMessage()."</p>"));
        }


        try
        {
            $consulta="select id_usuario,tipo from usuarios where usuario='".$_POST["usuario"]."' and clave='".md5($_POST["clave"])."'";
            $result=mysqli_query($conexion,$consulta);
        }
        catch(Exception $e)
        {
            mysqli_close($conexion);
            session_destroy();
            die(error_page("Práctica 10", "<h1>Práctica 10</h1><p>Error no se ha podido realizar la consulta: ".$e->getMessage()."</p>"));
        }

        mysqli_close($conexion);
        $tupla=mysqli_fetch_assoc($result);
        mysqli_free_result($result);

        if($tupla)
        {
            $_SESSION["id_usuario"]=$tupla["id_usuario"];
            $_SESSION["ultm_accion"]=time();

            if($tupla["tipo"]=="normal")
                header("Location:index.php");
            else
                header("Location:admin/gest_admin.php");
            exit;
        }
        else
        {
            $error_usuario=true;
           
        }    

    }
    
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 10</title>
    <style>
        .error{color:red}
        .mensaje{color:blue;font-size:1.25em}
    </style>
</head>
<body>
    <h1>Práctica 10</h1>
    <form action="index.php" method="post">
        <p>
            <label for="usuario">Usuario: </label>
            <input type="text" name="usuario" id="usuario" value="<?php if(isset($_POST["usuario"])) echo $_POST["usuario"];?>">
            <?php
            if(isset($_POST["btnLogin"]) && $error_usuario)
            {
                if($_POST["usuario"]=="")
                    echo "<span class='error'> Campo vacío</span>";
                else
                    echo "<span class='error'> Credenciales inválidas</span>";
            }
                
            ?>
        </p>
        <p>
            <label for="clave">Contraseña: </label>
            <input type="password" name="clave" id="clave">
            <?php
            if(isset($_POST["btnLogin"]) && $error_clave)
                echo "<span class='error'> Campo vacío</span>";
            ?>
        </p>
        <p>
            <button type="submit" name="btnLogin">Login</button>
        </p>
    </form>
    <?php
    if(isset($_SESSION["seguridad"]))
    {
        echo "<p class='mensaje'>".$_SESSION["seguridad"]."</p>";
        session_destroy();
    }
    ?>
</body>
</html>