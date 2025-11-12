<?php


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 10</title>
    <style>
        .en_linea{display: inline;}
        .enlace{background:none;border:none;text-decoration: underline;color:blue;cursor:pointer}
    </style>
</head>
<body>
    <h1>Práctica 10</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usu_log["usuario"];?></strong> - 
        <form action="index.php" method="post" class="en_linea">
            <button type="submit" class="enlace" name="btnCerrarSesion">Salir</button>
        </form>
    </div>
      <h3>Soy normal</h3>
</body>
</html>