<?php
class Fruta3 {
    private $color;
    private $tam; // Tamaño
    static private $n_frutas = 0;

    public function __construct($nuevoColor, $nuevoTam) {
        $this->color = $nuevoColor;
        $this->tam = $nuevoTam;
        self::$n_frutas++;
    }

    // Función que se ejecuta cuando se destruye la instancia (null)
    public function __destruct() {
        self::$n_frutas--;
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
    public function imprimir() {
        echo "Color: <strong>$this->color</strong> Tamaño: <strong>$this->tam</strong>";
    }

    // Getter para las frutas (variable estatica)
    public static function get_num_frutas() {
        return self::$n_frutas;
    }
}
$manzana = new Fruta3("Verde", "Grande");
$pera = new Fruta3("Verde", "Pequeña");
$fresa = new Fruta3("Roja", "Pequeña");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 3 - POO</title>
</head>

<body>
    <h1>Ejercicio 3 - POO</h1>
    <h3>Características de la manzana</h3>
    <p><?= $manzana->imprimir() ?></p>
    <p><?= $pera->imprimir() ?></p>
    <p><?= $fresa->imprimir() ?></p>
    <p>Número de frutas: <strong><?= Fruta3::get_num_frutas() ?></strong></p>
    <?php $fresa = null ?>
    <p>Número de frutas: <strong><?= Fruta3::get_num_frutas() ?></strong></p>
</body>

</html>