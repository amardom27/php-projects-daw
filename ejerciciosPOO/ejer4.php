<?php
class Fruta4 {
    private $color;
    private $tam; // Tamaño

    public function __construct($nuevoColor, $nuevoTam) {
        $this->color = $nuevoColor;
        $this->tam = $nuevoTam;
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

    public function imprimir() {
        echo "Color: <strong>$this->color</strong> Tamaño: <strong>$this->tam</strong>";
    }
}

class Uva extends Fruta4 {
    private $tieneSemilla;

    public function __construct($color, $tam, $semilla) {
        parent::__construct($color, $tam);
        $this->tieneSemilla = $semilla;
    }

    public function get_tieneSemilla() {
        return $this->tieneSemilla;
    }

    public function set_tieneSemilla($semilla) {
        $this->tieneSemilla = $semilla;
    }

    public function imprimir() {
        echo "Color: <strong>" . parent::get_color() . "</strong> Tamaño: <strong>" . parent::get_tam() . "</strong> Semilla: <strong>" . ($this->get_tieneSemilla() ? "Sí" : "No") . "</strong>";
    }
}

$uva = new Uva("Negra", "Pequeña", true);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 4</title>
</head>

<body>
    <h1>Ejercicio 4 - POO</h1>
    <p><?= $uva->imprimir() ?></p>
    <p>La uva tiene semilla ? <strong><?php echo $uva->get_tieneSemilla() ? "Sí" : "No" ?></strong></p>
    <?php $uva->set_tieneSemilla(false); ?>
    <p>La uva tiene semilla ? <strong><?php echo $uva->get_tieneSemilla() ? "Sí" : "No" ?></strong></p>
</body>

</html>