<?php
function calcularDias($seg1, $seg2) {
    // 60 × 60 × 24 = 86400
    $diff = ceil(abs($seg1 - $seg2) / 86400);

    return $diff;
}

function es_formato_valido($fecha) {
    return checkdate(substr($fecha, 5, 2), substr($fecha, 8), substr($fecha, 0, 4));
}

if (isset($_POST["btnEnviar"])) {
    $fecha1 =  $_POST["fecha1"];
    $fecha2 =  $_POST["fecha2"];

    // Realmente no hace falta comprobar el formato
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
    <title>Ejercicio 3 - Fechas</title>
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
        <form action="ej3.php" method="post">
            <p>
                <label for="f1">Introduzca una fecha: </label>
                <input type="date" name="fecha1" id="f1" value="<?php if (isset($_POST["btnEnviar"])) echo $fecha1 ?>">
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
                <label for="f2">Introduzca otra fecha: </label>
                <input type="date" name="fecha2" id="f2" value="<?php if (isset($_POST["btnEnviar"])) echo $fecha2 ?>">
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
            // Devuelve la fecha en segundos desde 1/1/1970
            $fec1 = strtotime($fecha1);
            $fec2 = strtotime($fecha2);

            $diferencia = calcularDias($fec1, $fec2);
            echo "<p>La diferencia de dias entre las dos fechas introducidas es de " . $diferencia . " dias.</p>";
            ?>
        </div>
    <?php
    }
    ?>
</body>

</html>