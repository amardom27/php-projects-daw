<?php
const MESES = [
    "Enero",
    "Febrero",
    "Marzo",
    "Abril",
    "Mayo",
    "Junio",
    "Julio",
    "Agosto",
    "Septiembre",
    "Octubre",
    "Noviembre",
    "Diciembre"
];
const NUM_ANIOS = 25;

$anio_hoy = date("Y");

function calcularDias($seg1, $seg2) {
    // 60 × 60 × 24 = 86400
    $diff = ceil(abs($seg1 - $seg2) / 86400);

    return $diff;
}

if (isset($_POST["btnEnviar"])) {
    $d1 = trim($_POST["dia1"]);
    $m1 = trim($_POST["mes1"]);
    $a1 = trim($_POST["anio1"]);

    $d2 = trim($_POST["dia2"]);
    $m2 = trim($_POST["mes2"]);
    $a2 = trim($_POST["anio2"]);

    $error_fecha1 = !checkdate($m1, $d1, $a1);
    $error_fecha2 = !checkdate($m2, $d2, $a2);

    $error_form = $error_fecha1 || $error_fecha2;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 2 - Fechas</title>
    <style>
        .container {
            padding-block: 1rem;
            padding-left: 0.5rem;
            border: 2px solid black;
            margin-bottom: 1rem;

            h2 {
                text-align: center;
            }
        }

        .blue {
            background-color: lightblue;
        }

        .green {
            background-color: lightgreen;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <div class="container blue">
        <h2>Fechas - Formulario</h2>
        <form action="ej2.php" method="post">
            <p>Introduzca una fecha:</p>
            <p>
                <label for="d1">Día: </label>
                <select name="dia1" id="d1">
                    <?php
                    for ($i = 1; $i <= 31; $i++) {
                        $selected = (isset($_POST["btnEnviar"]) && $i == $d1) ? 'selected' : '';
                        echo "<option $selected>" . sprintf("%02d", $i) . "</option>";
                    }
                    ?>
                </select>

                &nbsp;
                <label for="m1">Mes: </label>
                <select name="mes1" id="m1">
                    <?php
                    for ($i = 0; $i < count(MESES); $i++) {
                        $selected = (isset($_POST["btnEnviar"]) && ($i + 1) == $m1) ? 'selected' : '';
                        echo "<option value='" . ($i + 1) . "' $selected>" . MESES[$i] . "</option>";
                    }
                    ?>
                </select>

                &nbsp;
                <label for="a1">Año: </label>
                <select name="anio1" id="a1">
                    <?php
                    for ($i = ($anio_hoy - NUM_ANIOS); $i <= ($anio_hoy + NUM_ANIOS); $i++) {
                        $selected = (isset($_POST["btnEnviar"]) && $i == $a1) ? 'selected' : '';
                        echo "<option $selected>$i</option>";
                    }
                    ?>
                </select>
                <?php
                if (isset($_POST["btnEnviar"]) && $error_form) {
                    if ($error_fecha1) {
                        echo "<span class='error'>* Fecha no valida.</span>";
                    }
                }
                ?>
            </p>

            <p>Introduzca otra fecha:</p>
            <p>
                <label for="d2">Día: </label>
                <select name="dia2" id="d2">
                    <?php
                    for ($i = 1; $i <= 31; $i++) {
                        $selected = (isset($_POST["btnEnviar"]) && $i == $d2) ? 'selected' : '';
                        echo "<option $selected>" . sprintf("%02d", $i) . "</option>";
                    }
                    ?>
                </select>

                &nbsp;
                <label for="m2">Mes: </label>
                <select name="mes2" id="m2">
                    <?php
                    for ($i = 0; $i < count(MESES); $i++) {
                        $selected = (isset($_POST["btnEnviar"]) && ($i + 1) == $m2) ? 'selected' : '';
                        echo "<option value='" . ($i + 1) . "' $selected>" . MESES[$i] . "</option>";
                    }
                    ?>
                </select>

                &nbsp;
                <label for="a2">Año: </label>
                <select name="anio2" id="a2">
                    <?php
                    for ($i = ($anio_hoy - NUM_ANIOS); $i <= ($anio_hoy + NUM_ANIOS); $i++) {
                        $selected = (isset($_POST["btnEnviar"]) && $i == $a2) ? 'selected' : '';
                        echo "<option $selected>$i</option>";
                    }
                    ?>
                </select>
                <?php
                if (isset($_POST["btnEnviar"]) && $error_form) {
                    if ($error_fecha2) {
                        echo "<span class='error'>* Fecha no valida.</span>";
                    }
                }
                ?>
            </p>
            <button type="submit" name="btnEnviar">Calcular</button>
        </form>
    </div>
    <?php if (isset($_POST["btnEnviar"]) && !$error_form) {
    ?>
        <div class="container green">
            <h2>Fechas - Resultado</h2>
            <?php
            // Devuelve la fecha en segundos desde 1/1/1970
            $fec1 = mktime(0, 0, 0, $m1, $d1, $a1);
            $fec2 = mktime(0, 0, 0, $m2, $d2, $a2);

            $diferencia = calcularDias($fec1, $fec2);
            echo "<p>La diferencia de dias entre las dos fechas introducidas es de " . $diferencia . " dias.</p>";
            ?>
        </div>
    <?php
    }
    ?>
</body>

</html>