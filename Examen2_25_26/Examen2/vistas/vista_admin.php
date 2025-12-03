<?php
try {
    $consulta = "select * from grupos";
    $result = mysqli_query($conexion, $consulta);

    $grupos = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
} catch (Exception $e) {
    mysqli_close($conexion);
    session_destroy();
    die(error_page("Examen2 - PHP", "<h1>Examen2 - PHP</h1><p>Error no se ha podido realizar la consulta: " . $e->getMessage() . "</p>"));
}


if (isset($_POST["btnQuitar"])) {
    try {
        $consulta = "delete from horario_lectivo where usuario = '" . $_POST["btnQuitar"] . "' and dia = '" . $_POST["dia"] . "' and hora = '" . $_POST["hora"] . "' and grupo = '" . $_POST["grupo"] . "'";
        mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(error_page("Examen2 - PHP", "<h1>Examen2 - PHP</h1><p>Error no se ha podido realizar la consulta: " . $e->getMessage() . "</p>"));
    }
    $_SESSION["dia"] = $_POST["dia"];
    $_SESSION["hora"] = $_POST["hora"];
    $_SESSION["grupo"] = $_POST["grupo"];

    $_SESSION["mensaje"] = "Profesor quitado con exito.";

    mysqli_close($conexion);
    header("Location: index.php");
    exit;
}

if (isset($_POST["btnAgregar"])) {
    try {
        $consulta = "insert into `horario_lectivo`(`usuario`, `dia`, `hora`, `grupo`, `aula`) values ('" . $_POST["prof"] . "','" . $_POST["dia"] . "','" . $_POST["hora"] . "','" . $_POST["grupo"] . "','" . $_POST["aula"] . "')";
        mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(error_page("Examen2 - PHP", "<h1>Examen2 - PHP</h1><p>Error no se ha podido realizar la consulta: " . $e->getMessage() . "</p>"));
    }

    $_SESSION["dia"] = $_POST["dia"];
    $_SESSION["hora"] = $_POST["hora"];
    $_SESSION["grupo"] = $_POST["grupo"];

    $_SESSION["mensaje"] = "Profesor agregado con exito.";

    mysqli_close($conexion);
    header("Location: index.php");
    exit;
}

if (isset($_SESSION["dia"])) {
    $_POST["dia"] = $_SESSION["dia"];
    $_POST["hora"] = $_SESSION["hora"];
    $_POST["grupo"] = $_SESSION["grupo"];

    unset($_SESSION["dia"]);
    unset($_SESSION["hora"]);
    unset($_SESSION["grupo"]);

    $_POST["btnEditar"] = true;
}

if (isset($_POST["btnEditar"])) {
    try {
        $consulta = "select id_horario, usuarios.id_usuario, usuarios.usuario as nomusu, dia, hora, grupos.nombre as nomgrupo, aulas.nombre as nomaula from horario_lectivo join grupos on horario_lectivo.grupo = grupos.id_grupo join usuarios on horario_lectivo.usuario = usuarios.id_usuario join aulas on horario_lectivo.aula = aulas.id_aula where horario_lectivo.grupo = '" . $_POST["grupo"] . "' and horario_lectivo.dia = '" . $_POST["dia"] . "' and horario_lectivo.hora = '" . $_POST["hora"] . "'";
        $result = mysqli_query($conexion, $consulta);

        $profesores = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(error_page("Examen2 - PHP", "<h1>Examen2 - PHP</h1><p>Error no se ha podido realizar la consulta: " . $e->getMessage() . "</p>"));
    }

    try {
        $consulta = "select * from usuarios where id_usuario not in (select horario_lectivo.usuario from horario_lectivo where horario_lectivo.grupo = '" . $_POST["grupo"] . "' and horario_lectivo.dia = '" . $_POST["dia"] . "' and horario_lectivo.hora = '" . $_POST["hora"] . "')";
        $result = mysqli_query($conexion, $consulta);

        $usuarios = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(error_page("Examen2 - PHP", "<h1>Examen2 - PHP</h1><p>Error no se ha podido realizar la consulta: " . $e->getMessage() . "</p>"));
    }

    try {
        $consulta = "select * from aulas";
        $result = mysqli_query($conexion, $consulta);

        $aulas = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(error_page("Examen2 - PHP", "<h1>Examen2 - PHP</h1><p>Error no se ha podido realizar la consulta: " . $e->getMessage() . "</p>"));
    }
}

if (isset($_POST["grupo"])) {
    try {
        $consulta = "select id_horario, usuarios.id_usuario, usuarios.usuario as nomusu, dia, hora, grupos.nombre as nomgrupo, aulas.nombre as nomaula from horario_lectivo join grupos on horario_lectivo.grupo = grupos.id_grupo join usuarios on horario_lectivo.usuario = usuarios.id_usuario join aulas on horario_lectivo.aula = aulas.id_aula where horario_lectivo.grupo = '" . $_POST["grupo"] . "'";
        $result = mysqli_query($conexion, $consulta);

        $horario_grupo = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(error_page("Examen2 - PHP", "<h1>Examen2 - PHP</h1><p>Error no se ha podido realizar la consulta: " . $e->getMessage() . "</p>"));
    }
    $matriz = [];
    foreach ($horario_grupo as $hor) {
        if (isset($matriz[$hor["dia"]][$hor["hora"]])) {
            $matriz[$hor["dia"]][$hor["hora"]] .= "<br>" . $hor["nomusu"] . "(" . $hor["nomaula"] . ")";
        } else {
            $matriz[$hor["dia"]][$hor["hora"]] = $hor["nomusu"] . "(" . $hor["nomaula"] . ")";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen 25 26</title>
    <style>
        .enlace {
            background: none;
            border: none;
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th {
            background-color: lightgray;
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            text-align: center;
        }

        .text-c {
            text-align: center;
        }

        .mensaje {
            color: blue;
        }

        .last-form {
            margin-top: 1rem;
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body>
    <h1>Examen 2 - PHP</h1>
    <form action="index.php" method="post">
        <p>
            Bienvenido <strong><?= $usuario["usuario"] ?></strong> -
            <button type="submit" name="btnSalir" class="enlace">Salir</button>
        </p>
    </form>
    <h2>Horario de los grupos</h2>
    <form action="index.php" method="post">
        <label for="grupo">Eliga el grupo: </label>
        <select name="grupo" id="grupo">
            <?php
            foreach ($grupos as $grupo) {
                if (isset($_POST["grupo"]) && $_POST["grupo"] == $grupo["id_grupo"]) {
                    $nombreGrupo = $grupo["nombre"];
                    echo "<option selected value='" . $grupo["id_grupo"] . "'>" . $grupo["nombre"] . "</option>";
                } else {
                    echo "<option value='" . $grupo["id_grupo"] . "'>" . $grupo["nombre"] . "</option>";
                }
            }
            ?>
        </select>
        <button type="submit" name="btnVerHorario">Ver Horario</button>
    </form>
    <?php if (isset($_POST["grupo"])) {
    ?>
        <h3 class='text-c'>Horario grupo: <?= $nombreGrupo ?></h3>
        <table>
            <tr>
                <th></th>
                <?php
                for ($i = 1; $i <= count(DIAS); $i++) {
                    echo "<th>" . DIAS[$i] . "</th>";
                }
                ?>
            </tr>
            <?php
            for ($i = 1; $i <= count(HORAS); $i++) {
                echo "<tr>";
                echo "<th>" . HORAS[$i] . "</th>";

                if ($i == 4) {
                    echo "<td colspan='5'>RECREO</td>";
                    continue;
                }

                for ($j = 1; $j <= count(DIAS); $j++) {
                    if (isset($matriz[$j][$i])) {
                        echo "<td>";
                        echo "<form action='index.php' method='post'>";
                        echo $matriz[$j][$i];
                        echo "<br>";
                        echo "<button class='enlace' type='submit' name='btnEditar'>Editar</button>";
                        echo "<input type='hidden' name='dia' value='" . $j . "'>";
                        echo "<input type='hidden' name='hora' value='" . $i . "'>";
                        echo "<input type='hidden' name='grupo' value='" . $_POST["grupo"] . "'>";
                        echo "</form>";
                        echo "</td>";
                    } else {
                        echo "<td>";
                        echo "<form action='index.php' method='post'>";
                        echo "<button class='enlace' type='submit' name='btnEditar'>Editar</button>";
                        echo "<input type='hidden' name='dia' value='" . $j . "'>";
                        echo "<input type='hidden' name='hora' value='" . $i . "'>";
                        echo "<input type='hidden' name='grupo' value='" . $_POST["grupo"] . "'>";
                        echo "</form>";
                        echo "</td>";
                    }
                }
                echo "</tr>";
            }
            ?>
        </table>
    <?php
    } ?>
    <?php
    if (isset($_POST["btnEditar"])) {
    ?>
        <h3>Editando la <?= $_POST["hora"] ?>ºHora (<?= HORAS[$_POST["hora"]] ?>) del <?= DIAS[$_POST["dia"]] ?></h3>
        <?php
        if (isset($_SESSION["mensaje"])) {
            echo "<p class='mensaje'>" . $_SESSION["mensaje"] . "</p>";
            unset($_SESSION["mensaje"]);
        }
        ?>
        <table>
            <tr>
                <th>Profesor(Aula)</th>
                <th>Acción</th>
            </tr>
            <?php
            foreach ($profesores as $prof) {
                echo "<tr>";
                echo "<td>" . $prof["nomusu"] . " (" . $prof["nomaula"] . ")</td>";
                echo "<td>";
                echo "<form action='index.php' method='post'>";
                echo "<button class='enlace' type='submit' name='btnQuitar' value='" . $prof["id_usuario"] . "'>Quitar</button>";
                echo "<input type='hidden' name='dia' value='" . $_POST["dia"] . "'>";
                echo "<input type='hidden' name='hora' value='" . $_POST["hora"] . "'>";
                echo "<input type='hidden' name='grupo' value='" . $_POST["grupo"] . "'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <div class='last-form'>
            <form action="index.php" method="post">
                <label for="prof">Eliga profesor: </label>
                <select name="prof" id="prof">
                    <?php
                    foreach ($usuarios as $usu) {
                        echo "<option value='" . $usu["id_usuario"] . "'>" . $usu["nombre"] . "</option>";
                    }
                    ?>
                </select>
                <label for="aula">Eliga aula: </label>
                <select name="aula" id="aula">
                    <?php
                    foreach ($aulas as $au) {
                        echo "<option value='" . $au["id_aula"] . "'>" . $au["nombre"] . "</option>";
                    }
                    ?>
                </select>
                <?php
                echo "<input type='hidden' name='dia' value='" . $_POST["dia"] . "'>";
                echo "<input type='hidden' name='hora' value='" . $_POST["hora"] . "'>";
                echo "<input type='hidden' name='grupo' value='" . $_POST["grupo"] . "'>";
                ?>
                <button type="submit" name="btnAgregar">Añadir</button>
            </form>
        </div>
    <?php
    }
    ?>
</body>

</html>