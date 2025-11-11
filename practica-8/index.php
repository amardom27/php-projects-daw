<?php
const NOMBRE = "bd_cv";
const NOMBRE_NO_IMAGEN = "no_image.webp";
require("../const_globales/env.php");

// Incluir las funciones
require("./src/func_const.php");

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
if (isset($_POST["btnDetalle"]) || isset($_POST["btnBorrar"]) || isset($_POST["btnEditar"])) {
    if (isset($_POST["btnDetalle"])) {
        $id_us = $_POST["btnDetalle"];
    } elseif (isset($_POST["btnEditar"])) {
        $id_us = $_POST["btnEditar"];
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

// Comprobar los datos del formulario para crear un usuario
if (isset($_POST["btnContCrear"])) {
    $error_nombre = $_POST["nombre"] == "";
    $error_usuario = $_POST["usuario"] == "";

    // Comprobar el usuario repetido
    if (!$error_usuario) {
        $error_usuario = repetido($conexion, "usuarios", "usuario", $_POST["usuario"]);

        if (is_string($error_usuario)) {
            mysqli_close($conexion);
            die(error_page("Practica 8", "<p>Error, no se ha podido realizar la consulta " . $error_usuario));
        }
    }

    $error_clave = $_POST["clave"] == "";
    $error_dni = $_POST["dni"] == "" || !dni_bien_escrito($_POST["dni"]) || !dni_valido($_POST["dni"]);

    // Comprobar el dni repetido
    if (!$error_dni) {
        $error_dni = repetido($conexion, "usuarios", "dni", strtoupper($_POST["dni"]));

        if (is_string($error_dni)) {
            mysqli_close($conexion);
            die(error_page("Practica 8", "<p>Error, no se ha podido realizar la consulta " . $error_dni));
        }
    }

    $error_foto = $_FILES["foto"]["name"] == ""
        || $_FILES["foto"]["error"] // != 0 
        || $_FILES["foto"]["size"] > 10 * 1024 * 1024 // Pasarlo a bits
        || !tiene_extension($_FILES["foto"]["name"])
        || !mi_getimagesize($_FILES["foto"]);

    $error_form = $error_nombre || $error_usuario || $error_clave || $error_dni || $error_foto;

    if (!$error_form) {
        // Inserto con la imagen por defecto
        // Si se ha subido una foto, movemos la foto y actulizamos el nombre de la foto en la base de datos (formato: img_id.extension)

        try {
            $consulta = "insert into usuarios (nombre, usuario, clave, dni, sexo, foto) 
            values ('" . $_POST["nombre"] . "', '" . $_POST["usuario"] . "', '" . md5($_POST["clave"]) . " ', ' " . strtoupper($_POST["dni"]) . " ', ' " . $_POST["sexo"] . " ', no_image.webp)";
        } catch (Exception $e) {
            mysqli_close($conexion);
            die(error_page("Practica 8", "<p>Error, no se ha podido realizar la consulta " . $e->getMessage()));
        }

        $mesaje_accion = "Usuario insertado con éxito";

        if ($_FILES["foto"]["name"] != "") {
            $ultimo_id = mysqli_insert_id($conexion);
            $array_nombre = explode(".", $_FILES["foto"]["name"]);
            $ext = "." . end($array_nombre);
            $nombre_nuevo = "img_" . $ultimo_id . $ext;

            @$var = move_uploaded_file($_FILES["foto"]["tmp_name"], "image/" . $nombre_nuevo);
            if ($var) {
                try {
                    $consulta = "update usuarios set foto = '" . $nombre_nuevo . " ' where id_usuario = " . $ultimo_id;
                    $resultado_foto = mysqli_query($conexion, $consulta);
                } catch (Exception $e) {
                    unlink("images/" . $nombre_nuevo);
                    $mesaje_accion = "Usuario insertado con éxito, pero con la imagen por defecto.";
                }
            } else {
                $mesaje_accion = "Usuario insertado con éxito, pero con la imagen por defecto.";
            }
        }
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

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <h1>Practica 8</h1>
    <?php
    if (isset($_POST["btnDetalle"])):
        require "vistas/vista-detalle.php";
    endif;
    ?>

    <?php if (isset($_POST["btnConfBorrar"])): ?>
        <p><strong><?= $_POST["hid-nombre"] ?></strong> se ha borrado con éxito.</p>
        <form action="index.php">
            <button type="submit">Atrás</button>
        </form>
    <?php endif; ?>

    <?php
    if (isset($_POST["btnEditar"]) || (isset($_POST["btnConfEditar"]) && $error_form)) {
        if (isset($_POST["btnEditar"])) {
            // Cogemos los datos de la base de datos para rellenar el formulario
            if (mysqli_num_rows($resultado_detalle) === 0) {
                $error_no_existe = "El usuario ya no se encuentra registrado en la base de datos.";
            } else {
                $tupla = mysqli_fetch_assoc($resultado_detalle);

                $id_usuario = $tupla["id_usuario"];
                $nombre = $tupla["nombre"];
                $usuario = $tupla["usuario"];
                $dni = $tupla["dni"];
                $sexo = $tupla["sexo"];
                $foto_bd = $tupla["foto"];
            }
            // No olvidar hacer free
            mysqli_free_result($resultado_detalle);
        } else {
            // Cogemos los datos de los campos $_POST
            $id_usuario = $_POST["btnConfEditar"];
            $nombre = $_POST["nombre"];
            $usuario = $_POST["usuario"];
            $dni = $_POST["dni"];
            $sexo = $_POST["sexo"];
            $foto_bd = $_POST["foto_bd"];
        }

        // Mostramos el formulario o el mensaje de error
        if (isset($error_no_existe)) {
    ?>
            <p><?= $error_no_existe ?></p>
            <form action="index.php" method="post">
                <button type="submit">Atrás</button>
            </form>
        <?php
        } else {
        ?>
            <h3>Borrado de usuario con el id: <?= $id_usuario ?></h3>
            <form action="index.php" method="post" enctype="multipart/form-data">
                <p>
                    <label for="nombre">Nombre: </label><br>
                    <input type="text" name="nombre" id="nombre" placeholder="Nombre..." value="<?= $nombre ?>">
                </p>
                <p>
                    <label for="usuario">Usuario: </label><br>
                    <input type="text" name="usuario" id="usuario" placeholder="Usuario..." value="<?= $usuario ?>">
                </p>
                <p>
                    <label for="clave">Clave: </label><br>
                    <input type="password" name="clave" id="clave" placeholder="Teclee nueva contraseña">
                </p>
                <p>
                    <label for="dni">DNI: </label><br>
                    <input type="text" name="dni" id="dni" placeholder="DNI..." value="<?= $dni ?>">
                </p>
                <p>
                    <label for="sexo">Sexo: </label><br>
                    <input type="radio" name="sexo" id="hombre" value="hombre" <?php if ($sexo == "hombre") echo "checked" ?>><label for="hombre">Hombre</label>
                    <br>
                    <input type="radio" name="sexo" id="mujer" value="mujer" <?php if ($sexo == "mujer") echo "checked" ?>><label for="mujer">Mujer</label>
                </p>
                <p>
                    <label for="foto">Cambiar mi foto (Archivo imagen con extension, Max: 500kB)</label><br>
                    <input type="file" name="foto" id="foto" accept="image/*">
                </p>
                <p>
                    <button type="submit" name="btnContEditar" value="<?php echo $id_usuario ?>">Continuar</button>
                    <button type="submit">Atrás</button>
                </p>
            </form>
    <?php
        }
    }
    ?>

    <?php if (isset($_POST["btnBorrar"])): ?>
        <?php
        if (mysqli_num_rows($resultado_detalle)):
            $tupla = mysqli_fetch_assoc($resultado_detalle);
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
            <p>El usuario ya no se encuentra registrado en la base de datos.</p>
        <?php
        endif;
        mysqli_free_result($resultado_detalle);
        ?>
    <?php endif; ?>
    <?php
    // ? Formulario de crear usuario
    // TODO Mostrar los errores
    if (isset($_POST["btnCrear"]) || (isset($_POST["btnContCrear"]) && $error_form)): ?>
        <h2>Agregar nuevo usuario</h2>
        <form action="index.php" method="post" enctype="multipart/form-data">
            <p>
                <label for="nombre">Nombre: </label><br>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre..." value="<?php if (isset($_POST["nombre"])) echo $_POST["nombre"] ?>">
            </p>
            <p>
                <label for="usuario">Usuario: </label><br>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario..." value="<?php if (isset($_POST["usuario"])) echo $_POST["usuario"] ?>">
            </p>
            <p>
                <label for="clave">Clave: </label><br>
                <input type="password" name="clave" id="clave" placeholder="Clave...">
            </p>
            <p>
                <label for="dni">DNI: </label><br>
                <input type="password" name="dni" id="dni" placeholder="DNI..." value="<?php if (isset($_POST["dni"])) echo $_POST["dni"] ?>">
            </p>
            <p>
                <label for="sexo">Sexo: </label><br>
                <input type="radio" name="sexo" id="hombre" value="hombre" checked><label for="hombre">Hombre</label>
                <br>
                <input type="radio" name="sexo" id="mujer" value="mujer"><label for="mujer">Mujer</label>
            </p>
            <p>
                <label for="foto">Incluir mi foto (Archivo imagen con extension, Max: 500kB)</label><br>
                <input type="file" name="foto" id="foto" accept="image/*">
            </p>
            <p>
                <button type="submit" name="btnContCrear">Guardar Cambios</button>
                <button type="submit">Atrás</button>
            </p>
        </form>
    <?php endif; ?>
    <h3>Listado de los usuarios</h3>
    <?php if (mysqli_num_rows($resultado_usuarios) > 0) : ?>
        <table>
            <tr>
                <th>#</th>
                <th>Foto</th>
                <th>Nombre</th>
                <th>
                    <form action="index.php" method="post">
                        <button type="submit" name="btnCrear" class="btn">Usuarios+</button>
                    </form>
                </th>
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