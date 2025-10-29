<?php

echo "<h2>Detalles del usuario con ID: ".$id_usuario."</h2>";
        
if(mysqli_num_rows($result_detalle_usuario)>0)
{
    $tupla=mysqli_fetch_assoc($result_detalle_usuario);
    echo "<p><strong>Nombre: </strong>".$tupla["nombre"]."</p>";
    echo "<p><strong>Usuario: </strong>".$tupla["usuario"]."</p>";
    echo "<p><strong>Clave: </strong></p>";
    echo "<p><strong>DNI: </strong>".$tupla["dni"]."</p>";
    echo "<p><strong>Sexo: </strong>".$tupla["sexo"]."</p>";
    echo "<p><img src='Img/".$tupla["foto"]."' alt='Foto de Perfil' title='Foto de Perfil'></p>";
    
}
else
    echo "<p>El usuario ya no se encuentra registrado en la BD</p>";

echo "<form action='index.php' method='post'><button>Atrás</button></form>";
mysqli_free_result($result_detalle_usuario);
?>