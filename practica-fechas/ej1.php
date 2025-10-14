<?php
function es_formato_valido($fecha) {
    if (strlen($fecha) !== 10) {
        return false;
    }

    if ($fecha[2] !== "/" || $fecha[5] !== "/") {
        return false;
    }

    if (substr($fecha, 0, 2) < 1 || substr($fecha, 0, 2) > 31) {
        return false;
    }

    if (substr($fecha, 3, 2) < 1 || substr($fecha, 3, 2) > 12) {
        return false;
    }

    if (substr($fecha, 6) < 0) {
        return false;
    }

    // Formato fechas PHP: Month - Day - Year
    if (!checkdate(
        substr($fecha, 3, 2),
        substr($fecha, 0, 2),
        substr($fecha, 6)
    )) {
        return false;
    }

    return true;
}

function calcularDias($fecha1, $fecha2) {
    $seg1 = strtotime($fecha1);
    $seg2 = strtotime($fecha2);

    // 60 × 60 × 24 = 86400
    $diff = ceil(abs($seg1 - $seg2) / 86400);

    return $diff;
}

if (isset($_POST["btnEnviar"])) {
    $fecha1 = trim($_POST["fecha1"]);
    $fecha2 = trim($_POST["fecha2"]);

    $error_fecha1 = $fecha1 === "" || !es_formato_valido($fecha1);
    $error_fecha2 = $fecha2 === "" || !es_formato_valido($fecha2);

    $error_form = $error_fecha1 || $error_fecha2;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 1 - Fechas</title>
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
        <p>Dame dos fechas y te dire cuantos dias han pasado entre las dos.</p>
        <form action="ej1.php" method="post">
            <p>
                <label for="f1">Introduce una fecha (DD/MM/AAAA): </label>
                <input type="text" name="fecha1" id="f1" value="<?php if (isset($_POST["btnEnviar"])) echo $fecha1 ?>">
                <?php
                if (isset($_POST["btnEnviar"]) && $error_form) {
                    if ($fecha1 === "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    } elseif (!es_formato_valido($fecha1)) {
                        echo "<span class='error'>* Fecha no valida.</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="f2">Introduce otra fecha (DD/MM/AAAA): </label>
                <input type="text" name="fecha2" id="f2" value="<?php if (isset($_POST["btnEnviar"])) echo $fecha2 ?>">
                <?php
                if (isset($_POST["btnEnviar"]) && $error_form) {
                    if ($fecha2 === "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    } elseif (!es_formato_valido($fecha2)) {
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
            $diferencia = calcularDias($fecha1, $fecha2);
            echo "<p>La diferencia de dias entre las dos fechas introducidas es de " . $diferencia . " dias.</p>";
            ?>
        </div>
    <?php
    }
    ?>
</body>

</html>