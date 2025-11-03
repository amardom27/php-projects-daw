<?php
const DIAS = [
    1 => "Lunes",
    2 => "Martes",
    3 => "Miércoles",
    4 => "Jueves",
    5 => "Viernes"
];

const HORAS = [
    1 => "8:15 - 9:15",
    2 => "9:15 - 10:15",
    3 => "10:15 - 11:15",
    4 => "11:15 - 11:45",
    5 => "11:45 - 12:45",
    6 => "12:45 - 13:45",
    7 => "13:45 - 14:45"
];

function error_page($body) {
    return '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Solucion Ex 2 Miguel</title>
    </head>

    <body>' . $body . '</body>
    </html>';
}
