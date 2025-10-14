<?php
function es_todo_letras($texto) {
    for ($i = 0; $i < strlen($texto); $i++) {
        // Comparar con la tabla ASCII
        if (ord($texto[$i]) < ord("A") || ord($texto[$i]) > ord("z")) {
            return false;
        }
    }
    return true;
}
// Usando un funcion de PHP
// function es_todo_letras($texto) {
//     return ctype_alpha($texto);
// }

function es_todo_numeros($texto) {
    for ($i = 0; $i < strlen($texto); $i++) {
        // Comparar con la tabla ASCII
        if (!is_numeric($texto[$i])) {
            return false;
        }
    }
    return true;
}

// Usando is_numeric en todo el texto 
// function es_todo_numeros($texto) {
//     return is_numeric($texto);
// }

function comprobarRima($palabra1, $palabra2) {
    // Case insensitive
    $palabra1 = strtolower($palabra1);
    $palabra2 = strtolower($palabra2);

    if (
        $palabra1[strlen($palabra1) - 1] == $palabra2[strlen($palabra2) - 1]
        && $palabra1[strlen($palabra1) - 2] == $palabra2[strlen($palabra2) - 2]
        && $palabra1[strlen($palabra1) - 3] == $palabra2[strlen($palabra2) - 3]
    ) {
        return 3;
    } elseif (
        $palabra1[strlen($palabra1) - 1] == $palabra2[strlen($palabra2) - 1]
        && $palabra1[strlen($palabra1) - 2] == $palabra2[strlen($palabra2) - 2]
    ) {
        return 2;
    } else {
        return 1;
    }
}

if (isset($_POST["btnEnviar"])) {
    // Quitar los espacios en blanco
    $texto1 = trim($_POST["primera"]);
    $texto2 = trim($_POST["segunda"]);

    $error_primera = $texto1 == "" || strlen($texto1) < 3 || !es_todo_letras($texto1);
    $error_segunda = $texto2 == "" || strlen($texto2) < 3 || !es_todo_letras($texto2);

    $error_form = $error_primera || $error_segunda;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 1</title>
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
        <p>Dime dos palabras y te diré si riman o no.</p>
        <form action="ej1.php" method="post">
            <p>
                <label for="p1">Primera palabra: </label>
                <input type="text" name="primera" id="p1" value="<?php if (isset($texto1)) echo $texto1; ?>">
                <?php
                if (isset($_POST["btnEnviar"]) && $error_primera) {
                    if ($texto1 == "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    } elseif (strlen($texto1) < 3) {
                        echo "<span class='error'>* Al menos debe tener 3 caracteres.</span>";
                    } elseif (!es_todo_letras($texto1)) {
                        echo "<span class='error'>* Solo se admiten caracteres del alfabeto.</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="p2">Segunda palabra: </label>
                <input type="text" name="segunda" id="p2" value="<?php if (isset($texto2)) echo $texto2; ?>">
                <?php
                if (isset($_POST["btnEnviar"]) && $error_segunda) {
                    if ($texto2 == "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    } elseif (strlen($texto2) < 3) {
                        echo "<span class='error'>* Al menos debe tener 3 caracteres.</span>";
                    } elseif (!es_todo_letras($texto2)) {
                        echo "<span class='error'>* Solo se admiten caracteres del alfabeto.</span>";
                    }
                }
                ?>
            </p>
            <button type="submit" name="btnEnviar">Comparar</button>
        </form>
    </div>
    <?php if (isset($_POST["btnEnviar"]) && !$error_form) {
    ?>
        <div class="container green">
            <h2>Ripios - Resultado</h2>
            <?php
            $res = comprobarRima($texto1, $texto2);
            echo "<p>";
            switch ($res) {
                case 1:
                    echo "Las palabras <strong>" . $texto1 . "</strong> y <strong>" . $texto2 . "</strong> no riman.";
                    break;
                case 2:
                    echo "Las palabras <strong>" . $texto1 . "</strong> y <strong>" . $texto2 . "</strong> riman un poco.";
                    break;
                case 3:
                    echo "Las palabras <strong>" . $texto1 . "</strong> y <strong>" . $texto2 . "</strong> riman.";
                    break;
                default:
                    echo "Ha ocurrido un error inesperado.";
                    break;
            }
            echo "</p>";
            ?>
        </div>
    <?php
    }
    ?>
</body>

</html>