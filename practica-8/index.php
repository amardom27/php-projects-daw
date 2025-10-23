<?php

use Dom\Mysql;

const NOMBRE = "bd_cv";
const NOMBRE_NO_IMAGEN = "no_image.webp";
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

// Borrado de la base de datos
if (isset($_POST["btnConfBorrar"])) {
    try {
        $consulta = "delete from usuarios where id_usuario = " . $_POST["btnConfBorrar"];
        $resultado_detalle = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page("Practica BBDD", "<p>Error, no se ha podido realizar la consulta de los detalles " . $e->getMessage()));
    }

    // TODO: borrar la foto
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
if (isset($_POST["btnDetalle"]) || isset($_POST["btnBorrar"])) {
    if (isset($_POST["btnDetalle"])) {
        $id_us = $_POST["btnDetalle"];
    } else {
        $id_us = $_POST["btnBorrar"];
    }

    try {
        $consulta = "select * from usuarios where id_usuario = " . $id_us;
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

    <?php if (isset($_POST["btnConfBorrar"])): ?>
        <p><strong><?= $_POST["hid-nombre"] ?></strong> se ha borrado con éxito.</p>
        <form action="index.php">
            <button type="submit">Atrás</button>
        </form>
    <?php endif; ?>

    <?php if (isset($_POST["btnBorrar"])): ?>
        <?php
        if (mysqli_num_rows($resultado_detalle)):
            $tupla = mysqli_fetch_assoc($resultado_detalle)
        ?>
            <h3>Borrado de usuario con el id: <?= $tupla["id_usuario"] ?></h3>
            <p>¿Estás seguro de que quieres borrar a <strong><?= $tupla["nombre"] ?></strong>?</p>
            <form action="index.php" method="post">
                <button type="submit" name="btnConfBorrar" value="<?= $tupla["id_usuario"] ?>">Continuar</button>
                <button type="submit">Cancelar</button>
                <input type="hidden" name="hid-nombre" value="<?= $tupla["nombre"] ?>">
                <input type="hidden" name="hid-foto" value="<?= $tupla["foto"] ?>">
            </form>
        <?php
        else:
        ?>
            <p>No hay ningún usuario en la base de datos para borrar.</p>
        <?php
        endif;
        mysqli_free_result($resultado_detalle);
        ?>

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
                            <button class="btn" type="submit" name="btnEditar" value="<?= $tupla["id_usuario"] ?>">Editar</button>
                            -
                            <button class="btn" type="submit" name="btnBorrar" value="<?= $tupla["id_usuario"] ?>">Borrar</button>
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