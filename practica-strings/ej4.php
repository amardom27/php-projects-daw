<?php
$romanToArabic = [
    "I"    => 1,
    "V"    => 5,
    "X"    => 10,
    "L"    => 50,
    "C"    => 100,
    "D"    => 500,
    "M"    => 1000,
];

function comprobarNumRomano($numero) {
    return preg_match('/^[IVXLCDM]+$/i', $numero) === 1;
}

// function comprobarNumRomano2($numero) {
//     for ($i = 0; $i < strlen($numero); $i++) {
//         if (!isset($map[$numero[$i]])) {
//             return false;
//         }
//     }
//     return true;
// }

function convertirNumero($numero, $map) {
    $total = 0;
    for ($i = 0; $i < strlen($numero); $i++) {
        $total += $map[$numero[$i]];
    }
    return $total;
}

function esta_bien_ordenado($numero, $map) {
    for ($i = 0; $i < strlen($numero) - 1; $i++) {
        if ($map[$numero[$i]] < $map[$numero[$i + 1]]) {
            return false;
        }
    }
    return true;
}

// Comprobar si hay 5 seguidos seguidos
function tiene_cinco_seguidos($romano) {
    $longitud = strlen($romano);
    $contador = 1;

    for ($i = 1; $i < $longitud; $i++) {
        if ($romano[$i] === $romano[$i - 1]) {
            $contador++;
            if ($contador > 4) {
                return true;
            }
        } else {
            $contador = 1;
        }
    }
    return false;
}

// * Deberiamos haber tenido en cuenta la cantidad de veces
// que se puede repetir un simbolo para que no se pase del valor
// del simbolo anterior (se deberia usar el anterio en ese caso)
function repite_bien($numero) {
    $cont = [
        "M" => 4,
        "D" => 1,
        "C" => 4,
        "L" => 1,
        "X" => 4,
        "V" => 1,
        "I" => 4,
    ];

    for ($i = 0; $i < strlen($numero); $i++) {
        $cont[$numero[$i]]--;

        if ($cont[$numero[$i]] < 0) {
            return false;
        }
    }
    return true;
}

if (isset($_POST["btnEnviar"])) {
    $texto = strtoupper(trim($_POST["palabra"]));
    $error_palabra = $texto == "";
    $error_formato = !preg_match('/^[IVXLCDM]+$/i', $texto) || !esta_bien_ordenado($texto, $romanToArabic) || tiene_cinco_seguidos($texto);

    $error_form = $error_palabra || $error_formato;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 4</title>
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
        <h2>Romanos a árabes - Formulario</h2>
        <p>Dime un número en números romanos y lo convertiré a árabes</p>

        <form action="" method="post">
            <p>
                <label for="palabra">Número: </label>
                <input type="text" name="palabra" id="palabra" value="<?php if (isset($texto)) echo $texto; ?>">
                <?php
                if (isset($_POST["btnEnviar"]) && $error_palabra) {
                    if ($texto == "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    }
                }
                if (isset($_POST["btnEnviar"]) && $error_formato) {
                    echo "<span class='error'>* No es un número válido en notación romana.</span>";
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
            <h2>Romanos a árabes - Resultado</h2>

            <?php
            $res = convertirNumero($texto, $romanToArabic);
            echo "El número <strong>" . $texto . "</strong> se escribe en cifras árabes " . $res;
            ?>
        </div>
    <?php
    }
    ?>
</body>

</html>