<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practica BBDD</title>
    <style>
        table,
        td,
        th {
            border: 1px solid black;
            padding: 4px;
        }

        th {
            background-color: lightgray;
        }

        table {
            border-collapse: collapse;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php
    const NOMBRE = "bd_teoria";
    require("../const_globales/env.php");

    try {
        @$conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE);
        mysqli_set_charset($conexion, "utf8");
    } catch (Exception $e) {
        die("<p>Error, no se ha podido conectar a la base de datos " . $e->getMessage() . "</p></body></html>");
    }

    try {
        $consulta = "select cod_asig, denominacion from t_asignatura";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die("<p>Error, no se ha podido realizar la consulta " . $e->getMessage() . "</p></body></html>");
    }
    ?>
    <h1>Practica BBDD</h1>
    <form action="index.php" method="post">
        <label for="asig">Elija la asignatura: </label>
        <select name="asig" id="asig">
            <?php while ($tupla = mysqli_fetch_assoc($resultado)) :  ?>
                <option value="<?= $tupla["cod_asig"] ?>">
                    <?= htmlspecialchars($tupla["denominacion"]) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <p>
            <button type=" submit" name="btnEnviar">Ver notas</button>
        </p>
    </form>
    <?php
    if (isset($_POST["btnEnviar"])) {
        try {
            $consulta = "
                SELECT 
                    t_alumnos.nombre, 
                    t_notas.nota
                FROM 
                    t_notas
                JOIN 
                    t_alumnos ON t_notas.cod_alu = t_alumnos.cod_alu
                JOIN 
                    t_asignatura ON t_notas.cod_asig = t_asignatura.cod_asig
                WHERE 
                    t_asignatura.cod_asig = " . $_POST["asig"] . ";";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die("<p>Error, no se ha podido realizar la consulta " . $e->getMessage() . "</p></body></html>");
        }
    ?>
        <h3>Información de las notas</h3>
        <table>
            <tr>
                <th>Nombre Alumno</th>
                <th>Nota</th>
            </tr>

            <?php while ($tupla = mysqli_fetch_assoc($resultado)) : ?>
                <tr>
                    <td><?= htmlspecialchars($tupla["nombre"]) ?></td>
                    <td><?= htmlspecialchars($tupla["nota"]) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php
    }
    ?>
</body>

</html>