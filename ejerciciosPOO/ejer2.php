<?php
class Fruta2 {
    private $color;
    private $tam; // Tamaño

    public function __construct($nuevoColor, $nuevoTam) {
        $this->color = $nuevoColor;
        $this->tam = $nuevoTam;
        $this->imprimir();
    }

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

    // Metodo para imprimir por pantalla
    private function imprimir() {
        echo "Color: <strong>$this->color</strong> Tamaño: <strong>$this->tam</strong>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 2 - POO</title>
</head>

<body>
    <h1>Ejercicio 2 - POO</h1>
    <h3>Características de la manzana</h3>
    <?php
    // Creamos la instancia de la frutaa con el constructor
    $manzana = new Fruta2("Verde", "Grande");
    ?>

</body>

</html>