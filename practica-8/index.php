<?php
const NOMBRE = "bd_cv";
require("../const_globales/env.php");

function error_page($title, $body) {
    $html = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>' . $title . '</title>
            </head>
            <body>
            ' . $body . '
            </body>
            </html>';
    return $html;
}

// Realizamos la conexion a la base de datos
try {
    @$conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE);
    mysqli_set_charset($conexion, "utf8");
} catch (Exception $e) {
    die(error_page("Practica BBDD", "<p>Error, no se ha podido conectar a la base de datos " . $e->getMessage()));
}

// Realizamos la consulta a la base de datos
try {
    $consulta = "select id_usuario, nombre, foto from usuarios";
    $resultado_usuarios = mysqli_query($conexion, $consulta);
} catch (Exception $e) {
    mysqli_close($conexion);
    die(error_page("Practica BBDD", "<p>Error, no se ha podido realizar la consulta " . $e->getMessage()));
}

// Realizamos la consulta de los detalles si se ha pulsado el boton
if (isset($_POST["btnDetalle"])) {
    try {
        $consulta = "select * from usuarios where id_usuario = " . $_POST["btnDetalle"];
        $resultado_detalle = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page("Practica BBDD", "<p>Error, no se ha podido realizar la consulta de los detalles " . $e->getMessage()));
    }
}

if (isset($_POST["btnConfirmar"])) {
    try {
        $consulta = "delete from usuarios where id_usuario = " . $_POST["btnConfirmar"];
        $resultado_detalle = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page("Practica BBDD", "<p>Error, no se ha podido realizar la consulta de los detalles " . $e->getMessage()));
    }
}

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practica 8 - BBDD</title>
    <style>
        table,
        td,
        th {
            border: 1px solid black;
            padding: 8px;
        }

        th {
            background-color: lightgray;
        }

        table {
            border-collapse: collapse;
            text-align: center;
            width: 90%;
            margin: 0 auto;
        }

        img {
            width: 150px;
            height: auto;
        }

        .btn {
            background: none;
            border: none;
            text-decoration: underline;
            color: blue;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h1>Practica 8</h1>
    <?php if (isset($_POST["btnDetalle"])): ?>
        <?php require "vistas/vista-detalle.php" ?>
    <?php endif; ?>

    <?php if (isset($_POST["btnConfirmar"])): ?>
        <p><?= $_POST["hid-nombre"] ?> se ha borrado con éxito.</p>
    <?php endif; ?>

    <?php if (isset($_POST["btnBorrar"])): ?>
        <p>¿Estás seguro de que quieres borrar a <strong><?= $_POST["btnBorrar"] ?></strong>?</p>
        <form action="index.php" method="post">
            <button type="submit" name="btnConfirmar" value="<?= $_POST["hid-id"] ?>">Continuar</button>
            <button type="submit">Cancelar</button>
            <input type="hidden" name="hid-nombre" value="<?= $_POST["btnBorrar"] ?>">
        </form>
    <?php endif; ?>
    <h3>Listado de los usuarios</h3>
    <?php if (mysqli_num_rows($resultado_usuarios) > 0) : ?>
        <table>
            <tr>
                <th>#</th>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Usuarios+</th>
            </tr>
            <?php while ($tupla = mysqli_fetch_assoc($resultado_usuarios)) : ?>
                <tr>
                    <td><?= $tupla["id_usuario"] ?></td>
                    <td><img src="<?= "images/" . $tupla["foto"] ?>" alt="Foto de perfil" title="Foto de perfil"></td>
                    <td>
                        <form action="index.php" method="post">
                            <button class="btn" type="submit" name="btnDetalle" value="<?= $tupla["id_usuario"] ?>"><?= $tupla["nombre"] ?></button>
                        </form>
                    </td>
                    <td>
                        <form action="index.php" method="post">
                            <button class="btn" type="submit" name="btnEditar" value="<?= $tupla["nombre"] ?>">Editar</button>
                            -
                            <button class="btn" type="submit" name="btnBorrar" value="<?= $tupla["nombre"] ?>">Borrar</button>
                            <input type="hidden" name="hid-id" value="<?= $tupla["id_usuario"] ?>">
                        </form>
                    </td>
                </tr>
            <?php
            endwhile;
            mysqli_free_result($resultado_usuarios);
            ?>
        </table>
    <?php else : ?>
        <p>No hay ningún usuario en la base de datos.</p>
    <?php endif; ?>
</body>

</html>