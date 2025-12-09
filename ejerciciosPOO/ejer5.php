<?php
class Empleado {
    private $nombre;
    private $sueldo;

    public function __construct($nombre, $sueldo) {
        $this->nombre = $nombre;
        $this->sueldo = $sueldo;
    }

    public function imprimir() {
        echo "Empleado { Nombre: <strong>$this->nombre</strong>, Impuestos: <strong>" . ($this->sueldo > 3000 ? "Sí tiene que pagar" : "No tiene que pagar") . "</strong> }";
    }
}
$empleado = new Empleado("Pepe", 4000);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 5 - POO</title>
</head>

<body>
    <h1>Ejercicio 5 - POO</h1>
    <p><?php $empleado->imprimir() ?></p>
</body>

</html>