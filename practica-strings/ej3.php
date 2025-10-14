<?php
function es_todo_digitos($texto) {
    return ctype_digit($texto);
}

function es_todo_letras($texto) {
    return ctype_alpha($texto);
}

if (isset($_POST["btnEnviar"])) {
    $error_palabra = $_POST["palabra"] == "" || strlen($_POST["palabra"]) < 3 || !es_todo_letras($_POST["palabra"]);

    $error_form = $error_palabra;
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 3</title>
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
        <h2>Frases Palíndromas - Formulario</h2>
        <p>Dime una frase y te diré si es una frase palíndroma</p>

        <form action="" method="post">
            <p>
                <label for="palabra">Frase: </label>
                <input type="text" name="palabra" id="palabra" value="<?php if (isset($_POST["palabra"])) echo $_POST["palabra"]; ?>">
                <?php
                if (isset($_POST["btnEnviar"]) && $error_palabra) {
                    if ($_POST["palabra"] == "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    } elseif (strlen($_POST["palabra"]) < 3) {
                        echo "<span class='error'>* Al menos debe tener 3 caracteres.</span>";
                    } elseif (!es_todo_letras($_POST["palabra"])) {
                        echo "<span class='error'>* Solo se admiten caracteres del alfabeto.</span>";
                    }
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
            <h2>Frases Palíndromas - Resultado</h2>

            <?php
            if (comprobarPalabra($_POST["palabra"])) {
                echo "<strong>" . $_POST["palabra"] . "</strong> es una frase palíndroma.";
            } else {
                echo "<strong>" . $_POST["palabra"] . "</strong> NO es una frase palíndroma.";
            }
            ?>
        </div>
    <?php
    }
    ?>
</body>

</html>