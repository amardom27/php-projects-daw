<?php
session_name("Examen2_24_25");
session_start();

require "../../../const_globales/env.php";
const NOMBRE_BD = "bd_libreria_exam";
const TIEMPO_INACTIVIDAD = 100; // minutos

function error_page($title, $body) {
    $html = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    $html .= '<title>' . $title . '</title></head>';
    $html .= '<body>' . $body . '</body></html>';
    return $html;
}

function tiene_extension($nombre) {
    $arr = explode(".", $nombre);
    if (count($arr) > 1) {
        return end($arr);
    }
    return false;
}

function mi_getimagesize($size, $file) {
    $respuesta = false;
    if ($size > 0) {
        $respuesta = getimagesize($file);
    }
    return $respuesta;
}

function es_natural($number) {
    return $number > 0 && is_int((int)$number);
}

function es_numerico_pos($number) {
    return $number >= 0 && is_numeric($number);
}

function esta_repetido($conexion, $ref) {
    try {
        $consulta = "select referencia from libros where referencia = '" . $ref . "'";
        $res_repetido = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        session_destroy();
        mysqli_close($conexion);
        die(error_page("Página de inicio", "<p>Error en la consulta a la BD: " . $e->getMessage() . " </p>"));
    }

    if (mysqli_num_rows($res_repetido) > 0) {
        return true;
    }
    return false;
}

if (isset($_POST["btnSalir"])) {
    var_dump("hola");
    session_destroy();
    header("Location: ../index.php");
    exit;
}

if (isset($_SESSION["id_usuario"])):
    try {
        @$conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE_BD);
        mysqli_set_charset($conexion, "utf8");
    } catch (Exception $e) {
        session_destroy();
        die(error_page("Página de inicio", "<p>Error en la conexión a la BD: " . $e->getMessage() . " </p>"));
    }

    try {
        $consulta = "select * from libros";
        $res_libros = mysqli_query($conexion, $consulta);

        $array_libros = [];
        while ($tupla = mysqli_fetch_assoc($res_libros)) {
            $array_libros[] = $tupla;
        }
        mysqli_free_result($res_libros);
    } catch (Exception $e) {
        session_destroy();
        mysqli_close($conexion);
        die(error_page("Página de inicio", "<p>Error en la consulta a la BD: " . $e->getMessage() . " </p>"));
    }

    // ? Control de seguridad

    $salto = "../index.php";
    // * Baneo
    try {
        $consulta = "select * from usuarios where id_usuario = '" . $_SESSION["id_usuario"] . "'";
        $res_ban = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        session_destroy();
        mysqli_close($conexion);
        die(error_page("Página de inicio", "<p>Error en la consulta a la BD: " . $e->getMessage() . " </p>"));
    }
    $tupla_usu_log = mysqli_fetch_assoc($res_ban);
    mysqli_free_result($res_ban);

    if (!$tupla_usu_log) {
        session_unset();
        $_SESSION["seguridad"] = "Usted ya no se encuenta en la base de datos.";
        mysqli_close($conexion);

        header("Location: " . $salto);
        exit;
    };

    // * Inactividad
    if ((time() - $_SESSION["ultima_accion"]) > TIEMPO_INACTIVIDAD * 60) {
        session_unset();
        $_SESSION["seguridad"] = "Tiempo de sesión expirado. Por favor vuelva a loguearse.";
        mysqli_close($conexion);

        header("Location: " . $salto);
        exit;
    }
    $_SESSION["ultima_accion"] = time();

    if ($tupla_usu_log["tipo"] == "normal") {
        $_SESSION["id_usuario"] = $tupla_usu_log["id_usuario"];
        $_SESSION["ultima_accion"] = time();
        mysqli_close($conexion);

        header("Location: ../index.php");
        exit;
    }

    if (isset($_POST["btnAgregar"])) {
        $error_ref = $_POST["ref"] == "" || !es_natural($_POST["ref"]) || esta_repetido($conexion, $_POST["ref"]);
        $error_titulo = $_POST["titulo"] == "";
        $error_autor = $_POST["autor"] == "";
        $error_desc = $_POST["desc"] == "";
        $error_precio = $_POST["precio"] == "" || !es_numerico_pos($_POST["precio"]);
        $error_portada = $_FILES["portada"]["name"] != "" && (
            $_FILES["portada"]["error"] || $_FILES["portada"]["size"] > 750 * 1024 || !tiene_extension($_FILES["portada"]["name"]) || !mi_getimagesize($_FILES["portada"]["size"], $_FILES["portada"]["tmp_name"])
        );
        //var_dump(mime_content_type($_FILES["portada"]["tmp_name"]));

        $error_form = $error_ref || $error_titulo || $error_autor || $error_desc || $error_precio || $error_portada;

        if (!$error_form) {
            $nombre_img = "no_imagen.jpg";
            if ($_FILES["portada"] != "") {
                $ext = tiene_extension($_FILES["portada"]["name"]);
                $nombre_img = "img" . $_POST["ref"] . "." . $ext;
            }

            // ? INSERT
            try {
                $consulta = "insert into `libros`(`referencia`, `titulo`, `autor`, `descripcion`, `precio`) values ('" . $_POST["ref"] . "','" . $_POST["titulo"] . "','" . $_POST["autor"] . "','" . $_POST["desc"] . "','" . $_POST["precio"] . "')";
                mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                session_destroy();
                mysqli_close($conexion);
                die(error_page("Página de inicio", "<p>Error en la consulta a la BD: " . $e->getMessage() . " </p>"));
            }

            $mensaje_res = "Libro agregado correctamente.";

            // Guardar la foto
            @$var = move_uploaded_file($_FILES["portada"]["tmp_name"], "../Images/" . $nombre_img);
            if ($var) {
                try {
                    $consulta = "update libros set portada='" . $nombre_img . "' where referencia=" . $_POST["ref"];
                    mysqli_query($conexion, $consulta);
                } catch (Exception $e) {
                    unlink("Img/" . $nombre_img);
                    $mensaje_res = "Libro agregado correctamente pero con la imagen por defecto.";
                }
            } else {
                $mensaje_res = "Libro agregado correctamente pero con la imagen por defecto.";
            }

            $_SESSION["mensaje"] = $mensaje_res;
            mysqli_close($conexion);
            header("Location: gest_libros.php");
            exit;
        }
    }

    if (isset($_POST["btnDetalle"])) {
        try {
            $consulta = "select * from libros where referencia='" . $_POST["btnDetalle"] . "'";
            $res_detalle = mysqli_query($conexion, $consulta);

            $libro_detalle = mysqli_fetch_assoc($res_detalle);
            mysqli_free_result($res_detalle);
        } catch (Exception $e) {
            session_destroy();
            mysqli_close($conexion);
            die(error_page("Página de inicio", "<p>Error en la consulta a la BD: " . $e->getMessage() . " </p>"));
        }
    }

    if (isset($_POST["btnConfBorrar"])) {
        $ref = $_POST["btnConfBorrar"];

        // Obtenemos la imagen antes de borrar el libro
        try {
            $consulta = "select portada from libros where referencia = '" . $ref . "'";
            $res_img = mysqli_query($conexion, $consulta);
            $tupla_img = mysqli_fetch_assoc($res_img);
            mysqli_free_result($res_img);

            $imagen = $tupla_img["portada"];
        } catch (Exception $e) {
            session_destroy();
            mysqli_close($conexion);
            die(error_page("Página de inicio", "<p>Error en la consulta a la BD: " . $e->getMessage() . " </p>"));
        }

        // Borramos el libro
        // ? DELETE 
        try {
            $consulta = "delete from libros where referencia = '" . $ref . "'";
            mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            session_destroy();
            mysqli_close($conexion);
            die(error_page("Página de inicio", "<p>Error en la consulta a la BD: " . $e->getMessage() . " </p>"));
        }

        // Borramos la imagen (si no es la imagen por defecto)
        if ($imagen !== "no_imagen.jpg" && file_exists("../Images/$imagen")) {
            unlink("../Images/$imagen");
        }

        $mensaje_res = "El libro con referencia " . $_POST["btnConfBorrar"] . " ha sido borrado con éxito.";

        $_SESSION["mensaje"] = $mensaje_res;
        mysqli_close($conexion);
        header("Location: gest_libros.php");
        exit;
    }

    // Cogemos los libros lo ultimo para ver reflejado los cambios en la base de datos 
    try {
        $consulta = "select * from libros";
        $res_libros = mysqli_query($conexion, $consulta);

        $array_libros = [];
        while ($tupla = mysqli_fetch_assoc($res_libros)) {
            $array_libros[] = $tupla;
        }
        mysqli_free_result($res_libros);
    } catch (Exception $e) {
        session_destroy();
        mysqli_close($conexion);
        die(error_page("Página de inicio", "<p>Error en la consulta a la BD: " . $e->getMessage() . " </p>"));
    }
?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Página de inicio</title>
        <style>
            .error {
                color: red;
            }

            .libros-cont {
                display: flex;
                flex-wrap: wrap;
            }

            .libro {
                flex: 0 0 33%;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .libro img {
                width: 100%;
                max-width: 24rem;
            }

            .mensaje {
                color: blue;
            }

            .enlace {
                background: none;
                border: none;
                text-decoration: underline;
                color: blue;
                cursor: pointer
            }

            .tab-libros {
                width: 90%;
                text-align: center;
                margin: 0 auto;
                border-collapse: collapse;
            }

            .tab-libros,
            th,
            td {
                border: 1px solid black;
            }

            th {
                background-color: lightgray;
            }

            label {
                display: inline-block;
                width: 6rem;
            }

            input:not([type="file"]),
            textarea {
                width: 12rem;
            }

            form {
                margin-bottom: 0;
            }

            .verde {
                color: green;
            }
        </style>
    </head>

    <body>
        <h1>Librería</h1>
        <form action="gest_libros.php" method="post">
            <p>Bienvenido <strong><em><?= $tupla_usu_log["lector"] ?></em></strong> -
                <button class="enlace" type="submit" name="btnSalir">Salir</button>
            </p>
        </form>
        <?php
        if (isset($_SESSION["mensaje"])) {
            echo "<p class='verde'>" . $_SESSION["mensaje"] . "</p>";

            // Borrar el mensaje de accion, solo el mensaje!!
            unset($_SESSION["mensaje"]);
        }
        ?>

        <h2>Listado de los libros</h2>
        <?php
        echo "<table class='tab-libros'>";
        echo "<tr>";
        echo "<th>Ref</th>";
        echo "<th>Título</th>";
        echo "<th>Acción</th>";
        echo "</tr>";

        foreach ($array_libros as $tupla) {
            echo "<tr>";
            echo "<td>" . $tupla["referencia"] . "</td>";
            echo "<td>";
            echo "<form action='gest_libros.php' method='post'>";
            echo "<button type='submit' name='btnDetalle' class='enlace' value='" . $tupla["referencia"] . "'>" . $tupla["titulo"] . "</button>";
            echo "</form>";
            echo "</td>";
            echo "<td>";
            echo "<form action='gest_libros.php' method='post'>";
            echo "<button type='submit' name='btnBorrar' class='enlace' value='" . $tupla["referencia"] . "'>Borrar</button>";
            echo " - ";
            echo "<button type='submit' name='btnEditar' class='enlace'>Editar</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>
        <?php if (isset($libro_detalle)) {
        ?>
            <h2>Detalles del libro</h2>
            <p><strong>Referencia:</strong> <?= $libro_detalle["referencia"] ?></p>
            <p><strong>Título:</strong> <?= $libro_detalle["titulo"] ?></p>
            <p><strong>Autor:</strong> <?= $libro_detalle["autor"] ?></p>
            <p><strong>Descripción:</strong>
                <?= $libro_detalle["descripcion"] ?>
            </p>
            <p><strong>Precio:</strong> <?= $libro_detalle["precio"] ?> €</p>
            <p><strong>Portada:</strong><br>
                <?php if ($libro_detalle["portada"] != "" && file_exists("../Images/" . $libro_detalle["portada"])): ?>
                    <img src="../Images/<?= $libro_detalle["portada"] ?>" alt="Portada" style="max-width:200px;">
                <?php else: ?>
                    <em>No disponible</em>
                <?php endif; ?>
            </p>
            <form action="gest_libros.php" method="post">
                <p><button type="submit" name="btnVolver">Volver</button></p>
            </form>
        <?php
        } elseif (isset($_POST["btnBorrar"])) {
        ?>
            <h2>Borrando el libro con referencia <?= $_POST["btnBorrar"] ?></h2>
            <p>Continuar borrando el libro ?</p>
            <form action="gest_libros.php" method="post">
                <button type="submit" name="btnConfBorrar" value="<?= $_POST["btnBorrar"] ?>">Confirmar</button>
                <button type="submit" name="btnVolver">Volver</button>
            </form>
        <?php
        } elseif (isset($_POST["btnEditar"])) {
        ?>
        <?php
        } else {
        ?>
            <h2>Agregar un nuevo libro</h2>
            <form action="gest_libros.php" method="post" enctype="multipart/form-data">
                <p>
                    <label for="ref">Referencia: </label>
                    <input type="text" name="ref" id="ref" value="<?php if (isset($_POST["btnAgregar"])) echo $_POST["ref"] ?>">
                    <?php
                    if (isset($_POST["btnAgregar"]) && $error_ref) {
                        if ($_POST["ref"] == "") {
                            echo "<span class='error'>* Campo obligatorio.</span>";
                        } elseif (esta_repetido($conexion, $_POST["ref"])) {
                            echo "<span class='error'>* Refencia repetida.</span>";
                        } elseif (!es_natural($_POST["ref"])) {
                            echo "<span class='error'>* Solo se admiten números y positivos.</span>";
                        } else {
                            echo "<span class='error'>* Error en la referencia.</span>";
                        }
                    }
                    ?>
                </p>
                <p>
                    <label for="titulo">Título</label>
                    <input type="text" name="titulo" id="titulo" value="<?php if (isset($_POST["btnAgregar"])) echo $_POST["titulo"] ?>">
                    <?php
                    if (isset($_POST["btnAgregar"]) && $error_titulo) {
                        if ($_POST["titulo"] == "") {
                            echo "<span class='error'>* Campo obligatorio.</span>";
                        }
                    }
                    ?>
                </p>
                <p>
                    <label for="autor">Autor</label>
                    <input type="text" name="autor" id="autor" value="<?php if (isset($_POST["btnAgregar"])) echo $_POST["autor"] ?>">
                    <?php
                    if (isset($_POST["btnAgregar"]) && $error_autor) {
                        if ($_POST["autor"] == "") {
                            echo "<span class='error'>* Campo obligatorio</span>";
                        }
                    }
                    ?>
                </p>
                <p>
                    <label for="desc">Descripción: </label>
                    <textarea name="desc" id="desc">
                    <?php if (isset($_POST["btnAgregar"])) echo $_POST["desc"] ?>
                </textarea>
                    <?php
                    if (isset($_POST["btnAgregar"]) && $error_desc) {
                        if ($_POST["desc"] == "") {
                            echo "<span class='error'>* Campo obligatorio</span>";
                        }
                    }
                    ?>
                </p>
                <p>
                    <label for="precio">Precio: </label>
                    <input type="text" name="precio" id="precio" value="<?php if (isset($_POST["btnAgregar"])) echo $_POST["precio"] ?>">
                    <?php
                    if (isset($_POST["btnAgregar"]) && $error_precio) {
                        if ($_POST["precio"] == "") {
                            echo "<span class='error'>* Campo obligatorio</span>";
                        } elseif (!es_numerico_pos($_POST["precio"])) {
                            echo "<span class='error'>* Solo se admiten números y positivos.</span>";
                        } else {
                            echo "<span class='error'>* Error en el precio.</span>";
                        }
                    }
                    ?>
                </p>
                <p>
                    <label for="portada">Portada: </label>
                    <input type="file" name="portada" id="portada">
                    <?php
                    if (isset($_POST["btnAgregar"]) && $error_portada) {
                        if ($_FILES["portada"]["error"]) {
                            echo "<span class='error'>* Error subiendo el archivo.</span>";
                        } elseif ($_FILES["portada"]["size"] > 750 * 1024) {
                            echo "<span class='error'>* Archivo demasiado grande. (Max: 750KB)</span>";
                        } elseif (!tiene_extension($_FILES["portada"]["name"])) {
                            echo "<span class='error'>* El archivo no tiene extensión.</span>";
                        } elseif (!mi_getimagesize($_FILES["portada"]["size"], $_FILES["portada"]["tmp_name"])) {
                            echo "<span class='error'>* El archivo no es un archivo imagen.</span>";
                        } else {
                            echo "<span class='error'>* Error en el archivo.</span>";
                        }
                    }
                    ?>
                </p>
                <p>
                    <button type="submit" name="btnAgregar">Agregar</button>
                </p>
            </form>
        <?php
        } ?>
    </body>

    </html>
<?php
else:
    session_destroy();

    header("Location: ../index.php");
    exit;
endif;
