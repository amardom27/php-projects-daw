<?php
require "../const_globales/env.php";
const NOMBRE_BD = "bd_cv";

// Forma normal
// try {
//     @$conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE_BD);
//     mysqli_set_charset($conexion, "utf8");
// } catch (Exception $e) {
//     die("<p>No se ha podido conectarse a la BD</p><p>" . $e->getMessage() . "</p>");
// }
// mysqli_close($conexion);

// Forma PDO
try {
    $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
} catch (PDOException $e) {
    die("<p>No se ha podido conectarse a la BD</p><p>" . $e->getMessage() . "</p>");
}

$usu = "normal";
$clave = "123456";

try {
    $consulta = "select * from usuarios where usuario = ? and clave = ?";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->execute([$usu, $clave]);
} catch (PDOException $e) {
    // Hay que cerrar la sentencia tambien (siempre !!)
    $sentencia = null;
    $conexion = null;
    die("<p>No se ha podido realizar la consulta a la BD</p><p>" . $e->getMessage() . "</p>");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teoria PDO</title>
</head>

<body>
    <p>Conectado a la BD</p>
    <?php
    if ($sentencia->rowCount() <= 0) {
        echo "No se han obtenido tuplas";
    } else {
        echo "Se han obtenido tuplas";

        $tupla = $sentencia->fetch(PDO::FETCH_ASSOC); // PDO::FETCH_NUM, ::FETCH_OBJECT
        // Cerramos la sentencia
        $sentencia = null;

        echo "<p>Usuario: " . $tupla["usuario"] . "</p>";
        echo "<p>Nombre: " . $tupla["nombre"] . "</p>";
    }

    try {
        $consulta = "select * from usuarios";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([]);
    } catch (PDOException $e) {
        // Hay que cerrar la sentencia tambien (siempre !!)
        $sentencia = null;
        $conexion = null;
        die("<p>No se ha podido realizar la consulta a la BD</p><p>" . $e->getMessage() . "</p>");
    }

    $usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    echo "<ol>";
    foreach ($usuarios as $usu) {
        echo "<li>" . $usu["usuario"] . "</li>";
    }
    echo "</ol>";

    $usuario = "jmora";
    $clave = md5("123456");
    $nombre = "Juan Mora";
    $dni = "88888888N";
    $sexo = "hombre";
    $imagen = "no_imagen.jpg";

    try {
        $consulta = "insert into usuarios (usuario, clave, nombre, dni, sexo, foto) values (?, ?, ?, ?, ?, ?)";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$usuario, $clave, $nombre, $dni, $sexo, $imagen]);
    } catch (PDOException $e) {
        // Hay que cerrar la sentencia tambien (siempre !!)
        $sentencia = null;
        $conexion = null;
        die("<p>No se ha podido realizar la consulta a la BD</p><p>" . $e->getMessage() . "</p>");
    }

    $sentencia = null;
    echo "<p>Usuario insertado con éxito con la id: " . $conexion->lastInsertId() . "</p>";

    $conexion = null; // mysqli_close($conexion);
    ?>
</body>

</html>