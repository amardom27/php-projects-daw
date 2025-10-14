<?php
$lettersPattern = '/^[A-Za-z]+$/';
$numbersPattern = '/^[0-9]+$/';

function es_todo_digitos($texto) {
    return ctype_digit($texto);
}

function es_todo_letras($texto) {
    return ctype_alpha($texto);
}

if (isset($_POST["btnEnviar"])) {
    $error_palabra = $_POST["palabra"] == "" || strlen($_POST["palabra"]) < 3;
    $error_formato = !es_todo_letras($_POST["palabra"]) && !es_todo_digitos($_POST["palabra"]);
    //$error_formato = !preg_match($lettersPattern, $_POST["palabra"]) && !preg_match($numbersPattern, $_POST["palabra"]);

    $error_form = $error_palabra || $error_formato;
}

function comprobarPalabra($palabra) {
    // Darle la vuelta a la palabra 
    $rev = strrev($palabra);

    for ($i = 0; $i < strlen($palabra); $i++) {
        if ($palabra[$i] !== $rev[$i]) {
            return false;
        }
    }
    return true;
}

function comprobarPalabraPunt($palabra) {
    $i = 0;
    $j = strlen($palabra) - 1;

    while ($i < $j) {
        if ($palabra[$i] !== $palabra[$j]) {
            return false;
        }
        $i++;
        $j--;
    }
    return true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 2</title>
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
        <h2>Ripios - Formulario</h2>
        <p>Dime una palabra o un número y te diré si es un palíndromo o un número capicúa</p>

        <form action="" method="post">
            <p>
                <label for="palabra">Palabra o número: </label>
                <input type="text" name="palabra" id="palabra" value="<?php if (isset($_POST["palabra"])) echo $_POST["palabra"]; ?>">
                <?php
                if (isset($_POST["btnEnviar"]) && $error_palabra) {
                    if ($_POST["palabra"] == "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    } elseif (strlen($_POST["palabra"]) < 3) {
                        echo "<span class='error'>* Al menos debe tener 3 caracteres.</span>";
                    }
                }
                if (isset($_POST["btnEnviar"]) && $error_formato) {
                    echo "<span class='error'>* Tiene que tener solo letras o solo números</span>";
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
            <h2>Palíndromos / capicúas - Resultado</h2>

            <?php
            if (preg_match($lettersPattern, $_POST["palabra"])) {
                if (comprobarPalabraPunt($_POST["palabra"])) {
                    echo "<strong>" . $_POST["palabra"] . "</strong> es un palíndromo.";
                } else {
                    echo "<strong>" . $_POST["palabra"] . "</strong> NO es un palíndromo.";
                }
            }
            if (preg_match($numbersPattern, $_POST["palabra"])) {
                if (comprobarPalabraPunt($_POST["palabra"])) {
                    echo "<strong>" . $_POST["palabra"] . "</strong> es un número capicúa.";
                } else {
                    echo "<strong>" . $_POST["palabra"] . "</strong> NO es un número capicúa.";
                }
            }
            ?>
        </div>
    <?php
    }
    ?>
</body>

</html>