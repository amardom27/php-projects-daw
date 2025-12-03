<?php
const SERVIDOR_BD = "localhost";
const USUARIO_BD = "jose";
const CLAVE_BD = "josefa";
const NOMBRE_BD = "bd_horarios_exam";

const TIEMPO_INACT = 10; //Tiempo en minutos

const DIAS = [
    1 => "Lunes",
    "Martes",
    "Miercoles",
    "Jueves",
    "Viernes"
];

const HORAS = [
    1 => "8:15 - 9:15",
    "9:15 - 10:15",
    "10:15 - 11:15",
    "11:15 - 12:45",
    "12:45 - 13:45",
    "13:45 - 14:45"
];

function error_page($title, $body) {
    $html = '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . $title . '</title>
    </head>
    <body>' . $body . '          
    </body>
    </html>';
    return $html;
}
