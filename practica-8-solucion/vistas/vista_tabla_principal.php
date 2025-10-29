<h3>Listado de los usuarios</h3>
<table>
    <tr>
        <th>#</th>
        <th>Foto</th>
        <th>Nombre</th>
        <th>
            <form action='index.php' method='post'><button class='enlace' name='btnCrear'>Usuarios+</button></form>
        </th>
    </tr>
    <?php
    while ($tupla = mysqli_fetch_assoc($result_usuarios)) {
        echo "<tr>";
        echo "<td>" . $tupla["id_usuario"] . "</td>";
        if ($tupla["foto"] == "no_imagen.jpg") {
            echo "<td><img src='Img/" . $tupla["foto"] . "' alt='Foto de perfil' title='Foto de Perfil'></td>";
        } else {
            echo "<td><form action='index.php' method='post'><button type='submit' name='btnBorrarFoto' value='" . $tupla["foto"] . "'><img src='Img/" . $tupla["foto"] . "' alt='Foto de perfil' title='Foto de Perfil'></button></form></td>";
        }
        echo "<td>";
        echo "<form action='index.php' method='post'>";
        echo "<button class='enlace' type='submit' name='btnDetalles' value='" . $tupla["id_usuario"] . "'>" . $tupla["nombre"] . "</button>";
        echo "</form>";
        echo "</td>";
        echo "<td><form action='index.php' method='post'><button class='enlace' name='btnBorrar' value='" . $tupla["id_usuario"] . "'>Borrar</button> - <button class='enlace' value='" . $tupla["id_usuario"] . "' name='btnEditar'>Editar</button></form></td>";
        echo "</tr>";
    }

    mysqli_free_result($result_usuarios);
    ?>
</table>