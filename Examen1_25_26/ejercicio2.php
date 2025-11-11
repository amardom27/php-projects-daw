<?php
const SERVIDOR = "localhost";
const USUARIO = "jose";
const CLAVE = "josefa";
const NOMBRE_BD = "bd_exam_colegio";

const MAX_SIZE = 2 * 1024 * 1024; // 2 MB

function error_page($body) {
    return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 2</title>
</head>
<body>
    <h1>Rellenar/Borrar las tablas de "bd_exam_colegio", desde un fichero de texto</h1>
    <p>No se ha podido relizar la consulta: ' . $body . ' </p>
</body>
</html>';
}

function tiene_extension($nombre) {
    $extension = false;
    $arr = explode(".", $nombre);
    if (count($arr) > 1) {
        $extension = end($arr);
    }
    return $extension;
}

try {
    $conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE_BD);
    mysqli_set_charset($conexion, "utf8");
} catch (Exception $e) {
    die(error_page($e->getMessage()));
}

if (isset($_FILES["fichero"]) && $_FILES["fichero"]["name"] != "") {
    $ext = tiene_extension($_FILES["fichero"]["name"]);
    $error_form = $_FILES["fichero"]["error"]
        || $_FILES["fichero"]["size"] > MAX_SIZE
        || $ext != "txt";

    $nombreNuevo = "fichero";
    if (!$error_form) {
        move_uploaded_file($_FILES["fichero"]["tmp_name"], "Ficheros/" . $nombreNuevo . "." . $ext);
    }
}

if (isset($_POST["btnRellenar"]) &&  isset($_FILES["fichero"]) != "") {
    @$fd = fopen("Ficheros/fichero.txt", "r");

    $nombreTabla = fgets($fd);
    $camposTabla = explode(";", fgets($fd));

    for ($i = 0; $i < count($camposTabla); $i++) {
        $camposTabla[$i] = trim($camposTabla[$i]);
    }

    $values = "(";
    for ($i = 0; $i < count($camposTabla); $i++) {
        if ($i == count($camposTabla) - 1) {
            $values .= trim($camposTabla[$i]);
        } else {
            $values .= trim($camposTabla[$i]) . ", ";
        }
    }
    $values .= ")";

    while ($linea = fgets($fd)) {
        $arrAux = explode(";", $linea);
        $str = "(";

        for ($i = 0; $i < count($arrAux); $i++) {
            if ($i == count($arrAux) - 1) {
                $str .= "'" . trim($arrAux[$i]) . "'";
            } else {
                $str .= "'" . trim($arrAux[$i]) . "', ";
            }
        }
        $str .= ")";

        try {
            $consulta = "insert into " . $nombreTabla . " " . $values . " values " . $str;
            mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die(error_page($e->getMessage()));
        }
    }

    fclose($fd);
}

if (isset($_POST["btnBorrar"]) && isset($_FILES["fichero"]) != "") {
    @$fd = fopen("Ficheros/fichero.txt", "r");

    $nombreTabla = fgets($fd);
    $camposTabla = explode(";", fgets($fd));

    for ($i = 0; $i < count($camposTabla); $i++) {
        $camposTabla[$i] = trim($camposTabla[$i]);
    }

    try {
        $consulta = "delete from " . $nombreTabla;
        mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page($e->getMessage()));
    }

    fclose($fd);
}

if ((isset($_POST["btnRellenar"]) || isset($_POST["btnBorrar"])) && ($_FILES["fichero"]["name"] != "" && !$error_form)) {
    try {
        $consulta = "select * from " . $nombreTabla;
        $resultado_datos = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(error_page($e->getMessage()));
    }
}

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 2</title>
    <style>
        .error {
            color: red;
        }

        table,
        td,
        th {
            border: 1px solid black;
        }

        table {
            border-collapse: collapse;
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Rellenar/Borrar las tablas de "bd_exam_colegio", desde un fichero de texto</h1>
    <form action="ejercicio2.php" method="post" enctype="multipart/form-data">
        <label for="fichero">Seleccione el fichero de texto con los datos de la tabla a rellenar/borrar: (Max: 2MB)</label>
        <input type="file" name="fichero" id="fichero" accept=".txt">
        <?php
        if (isset($_FILES["fichero"]) && ($_FILES["fichero"]["name"] != "" && $error_form)) {
            if ($_FILES["fichero"]["error"]) {
                echo "<span class='error'> * Error subiendo el fichero.</span>";
            } elseif ($_FILES["fichero"]["size"] > MAX_SIZE) {
                echo "<span class='error'> * El fichero supera el tamaño permitido</span>";
            } elseif ($ext != "txt") {
                echo "<span class='error'> * El fichero seleccionado no es un fichero de texto.</span>";
            }
        }
        ?>
        <p>
            <button type="submit" name="btnRellenar">Rellenar tabla</button>
            <button type="submit" name="btnBorrar">Borrar tabla</button>
        </p>
    </form>
    <?php if (isset($_POST["btnRellenar"]) && ($_FILES["fichero"]["name"] != "" && !$error_form)) {
        echo "<h3>Volcado del fichero a la tabla " . $nombreTabla . " realizado con éxito</h3>";
    } ?>
    <?php if (isset($_POST["btnBorrar"]) && ($_FILES["fichero"]["name"] != "" && !$error_form)) {
        echo "<h3>Borrado de la tabla " . $nombreTabla . " realizado con éxito</h3>";
    } ?>
    <?php
    if ((isset($_POST["btnRellenar"]) || isset($_POST["btnBorrar"])) && ($_FILES["fichero"]["name"] != "" && !$error_form)) {
        echo "<table>";
        echo "<caption>" . $nombreTabla . "</caption>";

        for ($i = 0; $i < count($camposTabla); $i++) {
            echo "<th>" . $camposTabla[$i] . "</th>";
        }

        while ($tupla = mysqli_fetch_assoc($resultado_datos)) {
            echo "<tr>";

            for ($i = 0; $i < count($camposTabla); $i++) {
                echo "<td>" . $tupla[$camposTabla[$i]] . "</td>";
            }

            echo "</tr>";
        }
        mysqli_free_result($resultado_datos);

        echo "</table>";
    }
    ?>

</body>

</html>