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

function es_natural($number) {
    return $number > 0 && is_int($number);
}

function es_numerico_pos($number) {
    return $number > 0 && is_numeric($number);
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

function realizar_consulta($conexion, $consulta) {
    $resultado = mysqli_query($conexion, $consulta);

    if (!$resultado) {
        throw new Exception("Error en la consulta: " . mysqli_error($conexion));
    }

    $datos = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $datos[] = $fila;
    }

    mysqli_free_result($resultado);

    return $datos;
}

function ejecutar_modificacion($conexion, $consulta) {

    $resultado = mysqli_query($conexion, $consulta);

    if (!$resultado) {
        throw new Exception("Error en la consulta: " . mysqli_error($conexion));
    }

    // Número de filas afectadas (INSERT, UPDATE, DELETE)
    return mysqli_affected_rows($conexion);
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

    // Se usa así porque se va a usar en ambas vistas
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
            $_FILES["portada"]["error"] || $_FILES["portada"]["size"] > 750 * 1024 || !tiene_extension($_FILES["portada"]["name"])
        );
        //var_dump(mime_content_type($_FILES["portada"]["tmp_name"]));

        $error_form = $error_ref || $error_titulo || $error_autor || $error_desc || $error_precio || $error_portada;

        if (!$error_form) {
            try {
                $consulta = "";
                $libros = realizar_consulta($conexion, $consulta);
            } catch (Exception $e) {
                session_destroy();
                mysqli_close($conexion);
                die("Error: " . $e->getMessage());
            }
        }
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
        </style>
    </head>

    <body>
        <h1>Librería</h1>
        <form action="gest_libros.php" method="post">
            <p>Bienvenido <strong><em><?= $tupla_usu_log["lector"] ?></em></strong> -
                <button class="enlace" type="submit" name="btnSalir">Salir</button>
            </p>
        </form>

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
            echo "<td>" . $tupla["titulo"] . "</td>";
            echo "<td>";
            echo "<button type='submit' name='btnBorrar' class='enlace'>Borrar</button>";
            echo " - ";
            echo "<button type='submit' name='btnEditar' class='enlace'>Editar</button>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>

        <h2>Agregar un nuevo libro</h2>
        <form action="gest_libros.php" method="post" enctype="multipart/form-data">
            <p>
                <label for="ref">Referencia: </label>
                <input type="text" name="ref" id="ref" value="<?php if (isset($_POST["btnEnviar"])) echo $_POST["ref"] ?>">
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
                <input type="text" name="titulo" id="titulo">
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
                <input type="text" name="autor" id="autor">
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
                <textarea name="desc" id="desc"></textarea>
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
                <input type="text" name="precio" id="precio">
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
    </body>

    </html>
<?php
else:
    session_destroy();

    header("Location: ../index.php");
    exit;
endif;
