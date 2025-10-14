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
    <?php
    $lenguajes_cliente = array("JavaScript", "ReactJS", "Angular");
    $lenguajes_servidor = array("PHP", "Java", "GoLang");

    $lenguajes;
    foreach ($lenguajes_cliente as $key => $value) {
        $lenguajes[] = $value;
    }
    foreach ($lenguajes_servidor as $key => $value) {
        $lenguajes[] = $value;
    }
    ?>
    <table>
        <thead>
            <tr>
                <th>Index</th>
                <th>Language</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lenguajes as $index => $language) { ?>
                <tr>
                    <td><?= $index ?></td>
                    <td><?= $language ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>