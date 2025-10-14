<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teoria de fechas</title>
</head>

<body>
    <h1>Teoria de fechas</h1>
    <?php
    // Segundos que han pasado desde 1/1/1970
    $tiempo = time();
    echo "<p>" . $tiempo . "</p>";

    // Te muestra una fecha con formato
    // Parametros:
    // - formato de la fecha
    // - numero de segundos para hacer el calculo
    $fecha = date("d/m/Y, H:i:s", $tiempo);
    echo "<p>" . $fecha . "</p>";

    // Si no pones segundos usa la hora actual
    $fecha2 = date("d/m/Y, H:i:s");
    echo "<p>" . $fecha2 . "</p>";

    // Comprueba si la fecha existe
    // Parametros:
    // - mes    
    // - dia
    // - año
    $m = 2;
    $d = 10;
    $y = 2025;
    if (checkdate($m, $d, $y)) {
        echo "<p>La fecha existe.</p>";
    } else {
        echo "<p>La fecha NO existe.</p>";
    }

    $segundos_pasados = 542352342;
    $fecha3 = date("d/m/Y, H:i:s", $segundos_pasados);
    echo "<p>Fecha: " . $fecha3 . "</p>";

    // Segundo que han pasado desde 1970 hasta la fecha dada 
    // Parametros:
    // - hora
    // - minutos
    // - segundos
    // - mes
    // - dia
    // - año
    $segundos_quiros = mktime(6, 0, 15, 2, 7, 2006);
    echo "<p>Segundos (quiros): " . $segundos_quiros . "</p>";

    $fecha_quiros = date("d/m/Y, H:i:s", $segundos_quiros);
    echo "<p>" . $fecha_quiros . "</p>";

    // Obtener una fecha desde un string
    // Parametros (dos opciones)
    // - mes / año
    // - dia / mes
    // - año / dia
    // - hora
    // - minutos
    // - segundos
    $segundos_pasados2 = strtotime("02/07/2006 06:00:15");
    echo "<p>Segundos 2 (quiros): " . $segundos_pasados2 . "</p>";

    // Extra
    // Operaciones matematicas
    echo "<p>" . abs(-20) . "</p>";
    echo "<p>" . ceil(7.5) . "</p>";
    echo "<p>" . floor(7.5) . "</p>";
    ?>
</body>

</html>