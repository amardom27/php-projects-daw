<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table {
            border-collapse: collapse;
            font-family: sans-serif;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        thead {
            background-color: #f4f4f4;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Ejercicio 14</h1>
    <?php
    $estadios_futbol = array("Barcelona" => "Camp Nou", "Real Madrid" => "Santiago Bernabeu", "Valencia" => "Mestalla", "Real Sociedad" => "Anoeta");
    ?>
    <table>
        <thead>
            <tr>
                <th>Index</th>
                <th>Language</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($estadios_futbol as $key => $value) { ?>
                <tr>
                    <td><?= $key ?></td>
                    <td><?= $value ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <ol>
        <?php
        unset($estadios_futbol["Real Madrid"]);

        foreach ($estadios_futbol as $key => $value) {
            echo "<li>Equipo: $key, Estadio: $value</li>";
        }
        ?>
    </ol>
</body>

</html>