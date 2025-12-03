<?php
try {
    $consulta = "select id_horario, usuario, dia, hora, grupos.nombre as nomgrupo, aulas.nombre as nomaula from horario_lectivo join grupos on horario_lectivo.grupo = grupos.id_grupo join aulas on horario_lectivo.aula = aulas.id_aula where horario_lectivo.usuario = '" . $_SESSION["id_usuario"] . "'";
    $result = mysqli_query($conexion, $consulta);

    $horario_prof = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
} catch (Exception $e) {
    mysqli_close($conexion);
    session_destroy();
    die(error_page("Examen2 - PHP", "<h1>Examen2 - PHP</h1><p>Error no se ha podido realizar la consulta: " . $e->getMessage() . "</p>"));
}
$matriz = [];
foreach ($horario_prof as $hor) {
    $matriz[$hor["dia"]][$hor["hora"]] = $hor;
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
            width: 100%;
            border-collapse: collapse;
            text-align: center;
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
    <h2>Su horario</h2>
    <h3>Horarios del profesor: <?= $usuario["nombre"] ?></h3>
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
                    echo $matriz[$j][$i]["nomaula"];
                    echo "<br>";
                    echo "(" . $matriz[$j][$i]["nomgrupo"] . ")";
                    echo "</td>";
                } else {
                    echo "<td></td>";
                }
            }

            echo "</tr>";
        }
        ?>
    </table>

</body>

</html>