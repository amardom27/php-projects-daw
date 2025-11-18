<h2>Listado de los libros</h2>
<?php
echo "<div class='libros-cont'>";
foreach ($array_libros as $tupla) {
    echo "<div class='libro'>";
    echo "<img src='Images/" . $tupla["portada"] . "' alt='Imagen portada'>";
    echo "<p>" . $tupla["titulo"] . " - " . $tupla["precio"] . " €</p>";
    echo "</div>";
}
echo "</div>";
?>