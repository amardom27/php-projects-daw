<?php
require "src/funciones_ctes.php";

//Código para conectarme a una base de datos

try {
    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    mysqli_set_charset($conexion, "utf8");
} catch (Exception $e) {
    die(error_page("Práctica 8", "<h1>Práctica 8</h1><p>Error no se ha podido conectar a la BD: " . $e->getMessage() . "</p>"));
}

if (isset($_POST["btnContBorrarFoto"])) {
    try {
        $consulta = "update usuarios set foto = '" . NOMBRE_NO_IMAGEN_BD . "' where foto = '" . $_POST["btnContBorrarFoto"] . "'";
        $result_usuarios = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page("Práctica 8", "<h1>Práctica 8</h1><p>Error no se ha podido realizar la consulta: " . $e->getMessage() . "</p>"));
    }
    unlink("Img/" . $_POST["btnContBorrarFoto"]);
}

if (isset($_POST["btnContEditar"])) {
    $error_nombre = $_POST["nombre"] == "";
    $error_usuario = $_POST["usuario"] == "";
    if (!$error_usuario) {
        $error_usuario = repetido_edit($conexion, "usuarios", "usuario", $_POST["usuario"], "id_usuario", $_POST["btnContEditar"]);
        if (is_string($error_usuario)) {
            mysqli_close($conexion);
            die(error_page("Práctica 8", "<h1>Práctica 8</h1><p>Error no se ha podido realizar la consulta: " . $error_usuario . "</p>"));
        }
    }
    $error_dni = $_POST["dni"] == "" || !dni_bien_escrito($_POST["dni"]) || !dni_valido($_POST["dni"]);
    if (!$error_dni) {
        $error_dni = repetido_edit($conexion, "usuarios", "dni", strtoupper($_POST["dni"]), "id_usuario", $_POST["btnContEditar"]);
        if (is_string($error_dni)) {
            mysqli_close($conexion);
            die(error_page("Práctica 8", "<h1>Práctica 8</h1><p>Error no se ha podido realizar la consulta: " . $error_dni . "</p>"));
        }
    }

    $error_foto = $_FILES["foto"]["name"] != "" && ($_FILES["foto"]["error"] || $_FILES["foto"]["size"] > 500 * 1024 || !tiene_extension($_FILES["foto"]["name"]) || !mi_getimagesize($_FILES["foto"]));

    $error_form = $error_nombre || $error_usuario || $error_dni || $error_foto;

    if (!$error_form) {
        /// Actualizar Usuario
        try {
            if ($_POST["clave"] == "")
                $consulta = "update usuarios set nombre='" . $_POST["nombre"] . "', usuario='" . $_POST["usuario"] . "', dni='" . strtoupper($_POST["dni"]) . "', sexo='" . $_POST["sexo"] . "' where id_usuario='" . $_POST["btnContEditar"] . "'";
            else
                $consulta = "update usuarios set nombre='" . $_POST["nombre"] . "', usuario='" . $_POST["usuario"] . "', dni='" . strtoupper($_POST["dni"]) . "', clave='" . md5($_POST["clave"]) . "', sexo='" . $_POST["sexo"] . "' where id_usuario='" . $_POST["btnContEditar"] . "'";

            mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die(error_page("Práctica 8", "<h1>Práctica 8</h1><p>Error no se ha podido realizar la consulta: " . $e->getMessage() . "</p>"));
        }

        $mensaje_accion = "Usuario editado con éxito";
        if ($_FILES["foto"]["name"] != "") {
            $array_nombre = explode(".", $_FILES["foto"]["name"]);
            $ext = "." . end($array_nombre);
            $nombre_nuevo = "img_" . $_POST["btnContEditar"] . $ext;
            @$var = move_uploaded_file($_FILES["foto"]["tmp_name"], "Img/" . $nombre_nuevo);
            if ($var) {
                if ($nombre_nuevo != $_POST["foto_bd"]) {
                    try {
                        $consulta = "update usuarios set foto='" . $nombre_nuevo . "' where id_usuario='" . $_POST["btnContEditar"] . "'";
                        mysqli_query($conexion, $consulta);
                        if ($_POST["foto_bd"] != NOMBRE_NO_IMAGEN_BD) {
                            unlink("Img/" . $_POST["foto_bd"]);
                        }
                    } catch (Exception $e) {
                        unlink("Img/" . $nombre_nuevo);
                        $mensaje_accion = "Usuario editado con éxito, pero sin cambiar su imagen";
                    }
                }
            } else {
                $mensaje_accion = "Usuario editado con éxito, pero sin cambiar su imagen";
            }
        }
    }
}


if (isset($_POST["btnContCrear"])) {
    $error_nombre = $_POST["nombre"] == "";
    $error_usuario = $_POST["usuario"] == "";
    if (!$error_usuario) {
        $error_usuario = repetido($conexion, "usuarios", "usuario", $_POST["usuario"]);
        if (is_string($error_usuario)) {
            mysqli_close($conexion);
            die(error_page("Práctica 8", "<h1>Práctica 8</h1><p>Error no se ha podido realizar la consulta: " . $error_usuario . "</p>"));
        }
    }

    $error_clave = $_POST["clave"] == "";
    $error_dni = $_POST["dni"] == "" || !dni_bien_escrito($_POST["dni"]) || !dni_valido($_POST["dni"]);
    if (!$error_dni) {
        $error_dni = repetido($conexion, "usuarios", "dni", strtoupper($_POST["dni"]));
        if (is_string($error_dni)) {
            mysqli_close($conexion);
            die(error_page("Práctica 8", "<h1>Práctica 8</h1><p>Error no se ha podido realizar la consulta: " . $error_dni . "</p>"));
        }
    }

    $error_foto = $_FILES["foto"]["name"] != "" && ($_FILES["foto"]["error"] || $_FILES["foto"]["size"] > 500 * 1024 || !tiene_extension($_FILES["foto"]["name"]) || !mi_getimagesize($_FILES["foto"]));

    $error_form = $error_nombre || $error_usuario || $error_clave || $error_dni || $error_foto;

    if (!$error_form) {
        ///Inserto con imagen por defecto
        // Y si he subido foto, muevo la foto y actualizo el nombre de la foto en la BD ( img_id.extension)

        try {
            $consulta = "insert into usuarios (nombre, usuario, clave, dni,sexo) values ('" . $_POST["nombre"] . "','" . $_POST["usuario"] . "','" . md5($_POST["clave"]) . "','" . strtoupper($_POST["dni"]) . "','" . $_POST["sexo"] . "')";
            $result_usuarios = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die(error_page("Práctica 8", "<h1>Práctica 8</h1><p>Error no se ha podido realizar la consulta: " . $e->getMessage() . "</p>"));
        }

        $mensaje_accion = "Usuario insertado con éxito";
        if ($_FILES["foto"]["name"] != "") {
            $ultm_id = mysqli_insert_id($conexion);
            $array_nombre = explode(".", $_FILES["foto"]["name"]);
            $ext = "." . end($array_nombre);
            $nombre_nuevo = "img_" . $ultm_id . $ext;
            @$var = move_uploaded_file($_FILES["foto"]["tmp_name"], "Img/" . $nombre_nuevo);
            if ($var) {
                try {
                    $consulta = "update usuarios set foto='" . $nombre_nuevo . "' where id_usuario=" . $ultm_id;
                    $result_usuarios = mysqli_query($conexion, $consulta);
                } catch (Exception $e) {
                    unlink("Img/" . $nombre_nuevo);
                    $mensaje_accion = "Usuario insertado con éxito, pero con la imagen por defecto";
                }
            } else {
                $mensaje_accion = "Usuario insertado con éxito, pero con la imagen por defecto";
            }
        }
    }
}


if (isset($_POST["btnContBorrar"])) {
    try {
        $consulta = "delete from usuarios where id_usuario=" . $_POST["btnContBorrar"];
        $result_usuarios = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page("Práctica 8", "<h1>Práctica 8</h1><p>Error no se ha podido realizar la consulta: " . $e->getMessage() . "</p>"));
    }

    if ($_POST["foto"] != NOMBRE_NO_IMAGEN_BD && file_exists("Img/" . $_POST["foto"])) {
        unlink("Img/" . $_POST["foto"]);
    }
    $mensaje_accion = "¡¡ Usuario borrado con éxito !!";
}

if (isset($_POST["btnDetalles"]) || isset($_POST["btnBorrar"]) || isset($_POST["btnEditar"])) {
    if (isset($_POST["btnDetalles"]))
        $id_usuario = $_POST["btnDetalles"];
    elseif (isset($_POST["btnBorrar"]))
        $id_usuario = $_POST["btnBorrar"];
    else
        $id_usuario = $_POST["btnEditar"];


    try {
        $consulta = "select * from usuarios where id_usuario=" . $id_usuario;
        $result_detalle_usuario = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page("Práctica 8", "<h1>Práctica 8</h1><p>Error no se ha podido realizar la consulta: " . $e->getMessage() . "</p>"));
    }
}



//Realizo la consulta para mostrar usuarios de la tabla principal
try {
    $consulta = "select id_usuario, nombre, foto from usuarios";
    $result_usuarios = mysqli_query($conexion, $consulta);
} catch (Exception $e) {
    mysqli_close($conexion);
    die(error_page("Práctica 8", "<h1>Práctica 8</h1><p>Error no se ha podido realizar la consulta: " . $e->getMessage() . "</p>"));
}

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 8</title>
    <style>
        table,
        td,
        th {
            border: 1px solid black
        }

        table {
            border-collapse: collapse;
            text-align: center;
            width: 90%;
            margin: 0 auto
        }

        img {
            height: 100px
        }

        .enlace {
            background: none;
            border: none;
            color: blue;
            text-decoration: underline;
            cursor: pointer
        }

        .mensaje {
            font-size: 1.25em;
            color: blue
        }

        .error {
            color: red
        }
    </style>
</head>

<body>
    <h1>Práctica 8</h1>
    <?php
    if (isset($_POST["btnBorrarFoto"])) {
        echo "<h2>Borrar foto</h2>";
        echo "<p>¿Estas seguro de que quieres borrar la foto?";
        echo "<form action='index.php' method='post'>";
        echo "<p><button name='btnContBorrarFoto' value='" . $_POST["btnBorrarFoto"] . "'>Continuar</button> <button>Cancelar</button></p>";
        echo "</form>";
    }

    ?>
    <?php
    if (isset($mensaje_accion)) {
        echo "<p class='mensaje'>" . $mensaje_accion . "</p>";
    }

    if (isset($_POST["btnDetalles"])) {
        require "vistas/vista_detalles.php";
    }

    if (isset($_POST["btnBorrar"])) {
        echo "<h2>Borrado del usuario con ID: " . $id_usuario . "</h2>";
        if (mysqli_num_rows($result_detalle_usuario) > 0) {
            $tupla = mysqli_fetch_assoc($result_detalle_usuario);

            echo "<form action='index.php' method='post'>";
            echo "<p>¿ Estás seguro que quieres borrar a <strong>" . $tupla["nombre"] . "</strong> ?</p>";
            echo "<p><button name='btnContBorrar' value='" . $id_usuario . "'>Continuar</button> <button>Cancelar</button></p>";
            echo "<input type='hidden' value='" . $tupla["foto"] . "' name='foto'>";
            echo "</form>";
        } else {
            echo "<p>El usuario ya no se encuentra registrado en la BD</p>";
            echo "<form action='index.php' method='post'><button>Atrás</button></form>";
        }

        mysqli_free_result($result_detalle_usuario);
    }

    if (isset($_POST["btnEditar"]) || (isset($_POST["btnContEditar"]) && $error_form)) {
        if (isset($_POST["btnEditar"])) {
            //Cojo datos para rellenar formulario de la BD
            if (mysqli_num_rows($result_detalle_usuario) > 0) {
                $tupla = mysqli_fetch_assoc($result_detalle_usuario);

                $id_usuario = $tupla["id_usuario"];
                $nombre = $tupla["nombre"];
                $usuario = $tupla["usuario"];
                $dni = $tupla["dni"];
                $foto_bd = $tupla["foto"];
                $sexo = $tupla["sexo"];
            } else {
                $error_existencial = true;
            }
            mysqli_free_result($result_detalle_usuario);
        } else {
            //Cojo datos para rellenar formulario de los $_POST
            $id_usuario = $_POST["btnContEditar"];
            $nombre = $_POST["nombre"];
            $usuario = $_POST["usuario"];
            $dni = $_POST["dni"];
            $foto_bd = $_POST["foto_bd"];
            $sexo = $_POST["sexo"];
        }

        //Muestro formulario o mensaje de que ya no existe

        if (isset($error_existencial)) {
            echo "<p>El usuario ya no se encuentra registrado en la BD</p>";

            echo "<form action='index.php' method='post'><button>Atrás</button></form>";
        } else {
    ?>
            <h2>Editando al Usuario con ID: <?php echo $id_usuario; ?></h2>
            <form action="index.php" method="post" enctype="multipart/form-data">
                <p>
                    <label for="nombre">Nombre: </label><br>
                    <input type="text" name="nombre" id="nombre" placeholder="Nombre..." value="<?php echo $nombre; ?>">
                    <?php
                    if (isset($_POST["btnContEditar"]) && $error_nombre) {
                        echo "<span class='error'> * Campo vacío *</span>";
                    }
                    ?>
                </p>
                <p>
                    <label for="usuario">Usuario: </label><br>
                    <input type="text" name="usuario" id="usuario" placeholder="Usuario..." value="<?php echo $usuario; ?>">
                    <?php
                    if (isset($_POST["btnContEditar"]) && $error_usuario) {
                        if ($_POST["usuario"] == "")
                            echo "<span class='error'> * Campo vacío *</span>";
                        else
                            echo "<span class='error'> * Usuario repetido *</span>";
                    }
                    ?>
                </p>
                <p>
                    <label for="clave">Contraseña: </label><br>
                    <input type="password" name="clave" id="clave" placeholder="Teclee nueva contraseña">
                </p>
                <p>
                    <label for="dni">DNI: </label><br>
                    <input type="text" name="dni" id="dni" placeholder="DNI: 12345678Z" value="<?php echo $dni; ?>">
                    <?php
                    if (isset($_POST["btnContEditar"]) && $error_dni) {
                        if ($_POST["dni"] == "")
                            echo "<span class='error'> * Campo vacío *</span>";
                        elseif (!dni_bien_escrito($_POST["dni"]))
                            echo "<span class='error'> * Debes teclear 8 número y una letra *</span>";
                        elseif (!dni_valido($_POST["dni"]))
                            echo "<span class='error'> * DNI no válido *</span>";
                        else
                            echo "<span class='error'> * DNI repetido *</span>";
                    }
                    ?>
                </p>
                <p>
                    <label>Sexo:</label><br>
                    <input type="radio" name="sexo" value="hombre" id="hombre" <?php if ($sexo == "hombre") echo "checked"; ?>> <label for="hombre"> Hombre</label><br>
                    <input type="radio" name="sexo" value="mujer" id="mujer" <?php if ($sexo == "mujer") echo "checked"; ?>> <label for="mujer"> Mujer</label>
                </p>
                <p>
                    <img src="Img/<?= $foto_bd ?>" alt="Foto perfil" title="Foto perfil">
                    <label for="foto">Incluir mi foto (Archivo imagen con extensión, Máx. 500KB): </label>
                    <input type="file" name="foto" id="foto" accept="image/*">
                    <?php
                    if (isset($_POST["btnContEditar"]) && $error_foto) {
                        if ($_FILES["foto"]["error"]) {
                            echo "<span class='error'> * Error no se ha subido el fichero seleccionado al servidor *</span>";
                        } elseif (!tiene_extension($_FILES["foto"]["name"])) {
                            echo "<span class='error'> * El fichero seleccionado no tiene extensión *</span>";
                        } elseif (!mi_getimagesize($_FILES["foto"])) {
                            echo "<span class='error'> * El archivo seleccionado no es un archivo imagen *</span>";
                        } else {
                            echo "<span class='error'> * El archivo seleccionado supera los 500KB *</span>";
                        }
                    }
                    ?>
                </p>
                <p>
                    <input type="hidden" name="foto_bd" value="<?php echo $foto_bd; ?>">
                    <button type="submit" name="btnContEditar" value="<?php echo $id_usuario; ?>">Guardar cambios</button> <button type="submit">Atrás</button>
                </p>
            </form>
        <?php
        }
    }



    if (isset($_POST["btnCrear"]) || (isset($_POST["btnContCrear"]) && $error_form)) {
        ?>
        <h2>Agregar nuevo usuario</h2>
        <form action="index.php" method="post" enctype="multipart/form-data">
            <p>
                <label for="nombre">Nombre: </label><br>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre..." value="<?php if (isset($_POST["nombre"])) echo $_POST["nombre"]; ?>">
                <?php
                if (isset($_POST["btnContCrear"]) && $error_nombre) {
                    echo "<span class='error'> * Campo vacío *</span>";
                }
                ?>
            </p>
            <p>
                <label for="usuario">Usuario: </label><br>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario..." value="<?php if (isset($_POST["usuario"])) echo $_POST["usuario"]; ?>">
                <?php
                if (isset($_POST["btnContCrear"]) && $error_usuario) {
                    if ($_POST["usuario"] == "")
                        echo "<span class='error'> * Campo vacío *</span>";
                    else
                        echo "<span class='error'> * Usuario repetido *</span>";
                }
                ?>
            </p>
            <p>
                <label for="clave">Contraseña: </label><br>
                <input type="password" name="clave" id="clave" placeholder="Contraseña...">
                <?php
                if (isset($_POST["btnContCrear"]) && $error_clave) {
                    echo "<span class='error'> * Campo vacío *</span>";
                }
                ?>
            </p>
            <p>
                <label for="dni">DNI: </label><br>
                <input type="text" name="dni" id="dni" placeholder="DNI: 12345678Z" value="<?php if (isset($_POST["dni"])) echo $_POST["dni"]; ?>">
                <?php
                if (isset($_POST["btnContCrear"]) && $error_dni) {
                    if ($_POST["dni"] == "")
                        echo "<span class='error'> * Campo vacío *</span>";
                    elseif (!dni_bien_escrito($_POST["dni"]))
                        echo "<span class='error'> * Debes teclear 8 número y una letra *</span>";
                    elseif (!dni_valido($_POST["dni"]))
                        echo "<span class='error'> * DNI no válido *</span>";
                    else
                        echo "<span class='error'> * DNI repetido *</span>";
                }
                ?>
            </p>
            <p>
                <label>Sexo:</label><br>
                <input type="radio" name="sexo" value="hombre" id="hombre" <?php if (!isset($_POST["sexo"]) || $_POST["sexo"] == "hombre") echo "checked"; ?>> <label for="hombre"> Hombre</label><br>
                <input type="radio" name="sexo" value="mujer" id="mujer" <?php if (isset($_POST["sexo"]) && $_POST["sexo"] == "mujer") echo "checked"; ?>> <label for="mujer"> Mujer</label>
            </p>
            <p>
                <label for="foto">Incluir mi foto (Archivo imagen con extensión, Máx. 500KB): </label>
                <input type="file" name="foto" id="foto" accept="image/*">
                <?php
                if (isset($_POST["btnContCrear"]) && $error_foto) {
                    if ($_FILES["foto"]["error"]) {
                        echo "<span class='error'> * Error no se ha subido el fichero seleccionado al servidor *</span>";
                    } elseif (!tiene_extension($_FILES["foto"]["name"])) {
                        echo "<span class='error'> * El fichero seleccionado no tiene extensión *</span>";
                    } elseif (!mi_getimagesize($_FILES["foto"])) {
                        echo "<span class='error'> * El archivo seleccionado no es un archivo imagen *</span>";
                    } else {
                        echo "<span class='error'> * El archivo seleccionado supera los 500KB *</span>";
                    }
                }
                ?>
            </p>
            <p>
                <button type="submit" name="btnContCrear">Guardar cambios</button> <button type="submit">Atrás</button>
            </p>
        </form>
    <?php
    }

    require "vistas/vista_tabla_principal.php";
    ?>


</body>

</html>