<?php
class Fruta {
    private $color;
    private $tam; // Tamaño

    // El constructor vacio se crea solo

    // Getters
    public function get_color() {
        return $this->color;
    }

    public function get_tam() {
        return $this->tam;
    }

    // Setters
    public function set_color($nuevoColor) {
        $this->color = $nuevoColor;
    }

    public function set_tam($nuevoTam) {
        $this->tam = $nuevoTam;
    }
}

// Creamos la instancia de la frutaa
$manzana = new Fruta();

// Asignamos valores a sus propiedades
$manzana->set_color("Rojo");
$manzana->set_tam("Pequeña");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 1 - POO</title>
</head>

<body>
    <h1>Ejercicio 1 - POO</h1>
    <h3>Características de la manzana</h3>
    <p>Color de la fruta: <strong><?= $manzana->get_color() ?></strong></p>
    <p>Tamaño de la fruta: <strong><?= $manzana->get_tam() ?></strong></p>
</body>

</html>