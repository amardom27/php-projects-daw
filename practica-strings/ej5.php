<?php
$arabicToRoman = [
    1000 => "M",
    500  => "D",
    100  => "C",
    50   => "L",
    10   => "X",
    5    => "V",
    1    => "I",
];

if (isset($_POST["btnEnviar"])) {
    $error_palabra = $_POST["palabra"] == "" || (int)$_POST["palabra"] > 5000;
    $error_formato = !preg_match('/^[0-9]+$/', $_POST["palabra"]);

    $error_form = $error_palabra || $error_formato;
}

function comprobarNumArabe($numero) {
    return preg_match('/^[0-9]+$/', $numero) === 1;
}

function convertirNumero($numero, $map) {
    $resultado = "";

    // Recorremos el map con los valores de los numeros y las letras
    // Mientras el numero sea mayor que el valor decimal del map,
    // agrega la letra correspondiente y restamos el valor decimal del map
    // al numero que queremos convertir
    foreach ($map as $valor => $simbolo) {
        while ($numero >= $valor) {
            $resultado .= $simbolo;
            $numero -= $valor;

            if ($numero <= 0) {
                return $resultado;
            }
        }
    }

    return $resultado;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 5</title>
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
        <h2>Árabes a romanos - Formulario</h2>
        <p>Dime un número en números romanos y lo convertiré a árabes</p>

        <form action="" method="post">
            <p>
                <label for="palabra">Número: </label>
                <input type="text" name="palabra" id="palabra" value="<?php if (isset($_POST["palabra"])) echo $_POST["palabra"]; ?>">
                <?php
                if (isset($_POST["btnEnviar"]) && $error_palabra) {
                    if ($_POST["palabra"] == "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    } elseif ((int)$_POST["palabra"] > 5000) {
                        echo "<span class='error'>* El número no puede ser mayor que 5000.</span>";
                    }
                }
                if (isset($_POST["btnEnviar"]) && $error_formato) {
                    echo "<span class='error'>* No es un número válido en notación arabiga.</span>";
                }
                ?>
            </p>
            <button type="submit" name="btnEnviar">Comparar</button>
        </form>
    </div>
    <?php
    if (isset($_POST["btnEnviar"]) && !$error_form) {
    ?>
        <div class="container green">
            <h2>Árabes a romanos - Resultado</h2>

            <?php
            $res = convertirNumero($_POST["palabra"], $arabicToRoman);
            echo "El número <strong>" . $_POST["palabra"] . "</strong> se escribe en cifras árabes " . $res . ".";
            ?>
        </div>
    <?php
    }
    ?>
</body>

</html>