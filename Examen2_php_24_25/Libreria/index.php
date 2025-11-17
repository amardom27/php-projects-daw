<?php
session_name("Examen2_24_25");
session_start();

require "../../const_globales/env.php";
const NOMBRE_BD = "bd_libreria_exam";
const TIEMPO_INACTIVIDAD = 10; // minutos

function error_page($title, $body) {
    $html = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    $html .= '<title>' . $title . '</title></head>';
    $html .= '<body>' . $body . '</body></html>';
    return $html;
}

function create_td($tupla) {
    $html = "<td>";
    $html .= "<img src='./Images/" . $tupla["portada"] . "' alt='Imagen portada'>";
    $html .= "<p>" . $tupla["titulo"] . " - " . $tupla["precio"] . " €</p>";
    $html .= "</td>";
    return $html;
}

if (isset($_POST["btnSalir"])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

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

if (isset($_SESSION["id_usuario"])):
    $salto = "index.php";
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
    }

    // * Inactividad
    if ((time() - $_SESSION["ultima_accion"]) > TIEMPO_INACTIVIDAD * 60) {
        session_unset();
        $_SESSION["seguridad"] = "Tiempo de sesión expirado. Por favor vuelva a loguearse.";
        mysqli_close($conexion);

        header("Location: " . $salto);
        exit;
    }
    $_SESSION["ultima_accion"] = time();

    if ($tupla_usu_log["tipo"] == "admin") {
        mysqli_close($conexion);

        $_SESSION["id_usuario"] = $tupla_usu_log["id_usuario"];
        $_SESSION["ultima_accion"] = time();
        mysqli_close($conexion);

        header("Location: admin/gest_admin.php");
        exit;
    }

    // ? VISTA LOGUEADO
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Página de inicio</title>
    </head>

    <body>
        <h1>Librería</h1>
        <p>Bienvenido</p>
        <form action="index.php" method="post">
            <button type="submit" name="btnSalir">Salir</button>
        </form>
    </body>

    </html>
<?php
else:
    if (isset($_POST["btnLogin"])) {
        $error_usuario = $_POST["usuario"] == "";
        $error_clave = $_POST["clave"] == "";

        $error_form = $error_usuario || $error_clave;

        if (!$error_form) {
            try {
                $consulta = "select id_usuario, tipo from usuarios where lector = '" . $_POST["usuario"] . "' and clave = '" . md5($_POST["clave"]) . "'";
                $res_usuario = mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                session_destroy();
                mysqli_close($conexion);
                die(error_page("Página de inicio", "<p>Error en la consulta a la BD: " . $e->getMessage() . " </p>"));
            }

            $tupla = mysqli_fetch_assoc($res_usuario);
            mysqli_free_result($res_usuario);

            if ($tupla) {
                $_SESSION["id_usuario"] = $tupla["id_usuario"];
                $_SESSION["ultima_accion"] = time();
                mysqli_close($conexion);

                header("Location: index.php");
                exit;
            } else {
                $error_usuario = true;
            }
        }
    }
    // ? VISTA NORMAL
?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Página de Inicio</title>
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
        </style>
    </head>

    <body>
        <h1>Librería</h1>
        <form action="index.php" method="post">
            <p>
                <label for="usuario">Usuario: </label>
                <input type="text" name="usuario" id="usuario" value="<?php if (isset($_POST["usuario"])) echo $_POST["usuario"] ?>">
                <?php
                if (isset($_POST["btnLogin"]) && $error_usuario) {
                    if ($_POST["usuario"] == "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    } else {
                        // No olvidar cuando hacemos que haya error de usuario por credenciales inválidas
                        echo "<span class='error'>* Credenciales inválidas.</span>";
                    }
                }
                ?>
                <br>
                <label for="clave">Clave: </label>
                <input type="password" name="clave" id="clave">
                <?php
                if (isset($_POST["btnLogin"]) && $error_clave) {
                    echo "<span class='error'>* Campo obligatorio.</span>";
                }
                ?>
            </p>
            <button type="submit" name="btnLogin">Entrar</button>
        </form>
        <?php
        if (isset($_SESSION["seguridad"])) {
            echo "<p class='mensaje'>" . $_SESSION["seguridad"] . "</p>";
            session_destroy();
        }
        ?>
        <h2>Listado de los libros</h2>
        <?php
        echo "<div class='libros-cont'>";
        foreach ($array_libros as $tupla) {
            echo "<div class='libro'>";
            echo "<img src='Images/" . $tupla["portada"] . "' alt='Imagen portada'>";
            echo "<p>" . $tupla["titulo"] . " - " . $tupla["precio"] . " €</p>";
            echo "</div>";
        }
        echo "</div>";
        ?>
    </body>

    </html>
<?php
endif;
mysqli_close($conexion);
?>