<?php if (mysqli_num_rows($resultado_usuarios) > 0) : ?>
    <h3>Detalles del usuario con id <?= $_POST["btnDetalle"] ?></h3>
    <?php $tupla = mysqli_fetch_assoc($resultado_detalle); ?>
    <p><strong>Nombre: </strong><?= $tupla["nombre"] ?></p>
    <p><strong>Usuario: </strong><?= $tupla["usuario"] ?></p>
    <p><strong>Clave: </strong></p>
    <p><strong>DNI: </strong><?= $tupla["dni"] ?></p>
    <p><strong>Sexo: </strong><?= $tupla["sexo"] ?></p>
    <p><strong>Foto: </strong></p>
    <img src="<?= "images/" . $tupla["foto"] ?>" alt="Foto de perfil" title="Foto de perfil">
<?php else : ?>
    <p>El usuario ya no se encuentra registrado en la base de datos.</p>
<?php endif; ?>
<form action="index.php" method="post">
    <button type="submit">Atrás</button>
</form>
<?php mysqli_free_result($resultado_detalle); ?>