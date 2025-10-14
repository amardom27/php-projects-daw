<?php
function mi_in_array($text, $array) {
    for ($i=0; $i < count($array); $i++) { 
        if ($array[$i] == $text) {
            return true;
        }
    }
    return false;
}

// Formas de declarar constantes
define("PI", 3.14);
const PO = 41.3;
?>