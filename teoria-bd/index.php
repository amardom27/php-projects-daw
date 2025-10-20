<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teoria de BBDD</title>
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
    <h1>Teoria de BBDD</h1>
    <?php
    const NOMBRE = "bd_teoria";
    require("../const_globales/env.php");

    // Msqli usa un try catch para los errores, no se puede hacer con @ como en ficheros
    try {
        // Usamos la @ porque si falla el nombre del servidor nos salta un warning aunque este el try cath
        @$conexion = mysqli_connect(SERVIDOR, USUARIO, CLAVE, NOMBRE);
        // Hacer que la base de datos use la codificacion utf8
        mysqli_set_charset($conexion, "utf8");
    } catch (Exception $e) {
        die("<p>Error, no se ha podido conectar a la base de datos " . $e->getMessage() . "</p></body></html>");
    }

    echo "<h2>Conexion realizada con exito</h2>";

    try {
        // query para hacer las consultas a la base de datos
        // Tambien usa un try catch
        $consulta = "select * from t_alumnos";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        // NO olvidar cerrar la conexion
        // Aqui hay que cerrar porque ya estamos conectados y estamos terminando el programa 
        mysqli_close($conexion);

        die("<p>Error, no se ha podido realizar la consulta " . $e->getMessage() . "</p></body></html>");
    }
    echo "<h2>Consulta realizada con exito</h2>";

    $n_tuplas = mysqli_num_rows($resultado);
    echo "<p>El numero de filas obtenidas es " . $n_tuplas . "</p>";

    // Obtener como un array asociativo
    $tupla = mysqli_fetch_assoc($resultado);
    echo "<p>El telefono de " . $tupla["nombre"] . " es " . $tupla["telefono"] . "</p>";

    // Obtener como un objeto
    $tupla = mysqli_fetch_object($resultado);
    echo "<p>El telefono de " . $tupla->nombre . " es " . $tupla->telefono . "</p>";

    // Obtener como un array escalar
    $tupla = mysqli_fetch_row($resultado);
    echo "<p>El telefono de " . $tupla[1] . " es " . $tupla[2] . "</p>";

    // Si no hay mas te da NULL
    // $tupla = mysqli_fetch_row($resultado);

    // Para volver a una tupla determinada
    mysqli_data_seek($resultado, 1);

    $tupla = mysqli_fetch_assoc($resultado);
    echo "<p>El telefono de " . $tupla["nombre"] . " es " . $tupla["telefono"] . "</p>";

    mysqli_data_seek($resultado, 0);

    echo "<h3>Infomación de los alumnos</h3>";
    echo "<table>";
    echo "<tr>";

    echo "<th>Cod_Alu</th>";
    echo "<th>Nombre</th>";
    echo "<th>Telefono</th>";
    echo "<th>Cod_Postal</th>";

    echo "</tr>";

    while ($tupla = mysqli_fetch_assoc($resultado)) {
        echo "<tr>";

        echo "<td>" . $tupla["cod_alu"] . "</td>";
        echo "<td>" . $tupla["nombre"] . "</td>";
        echo "<td>" . $tupla["telefono"] . "</td>";
        echo "<td>" . $tupla["cp"] . "</td>";

        echo "</tr>";
    }

    echo "<table>";
    mysqli_data_seek($resultado, 0);
    ?>
    <h3>Información de los alumnos</h3>
    <table>
        <tr>
            <th>Cod_Alu</th>
            <th>Nombre</th>
            <th>Telefono</th>
            <th>Cod_Postal</th>
        </tr>

        <?php while ($tupla = mysqli_fetch_assoc($resultado)) : ?>
            <tr>
                <td><?= htmlspecialchars($tupla["cod_alu"]) ?></td>
                <td><?= htmlspecialchars($tupla["nombre"]) ?></td>
                <td><?= htmlspecialchars($tupla["telefono"]) ?></td>
                <td><?= htmlspecialchars($tupla["cp"]) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <?php
    // Liberar memoria cuando usamos un select (solo para el select)
    mysqli_free_result($resultado);

    // NO olvidar cerrar la conexion
    mysqli_close($conexion);
    ?>
</body>

</html>