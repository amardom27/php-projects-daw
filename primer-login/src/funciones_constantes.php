<?php
const NOMBRE_BD = "bd_cv";
const TIEMPO_INACTIVIDAD = 1; // Tiempo en minutos

function error_page($title, $body) {
    return '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . $title . '</title>
    </head>
    <body>' . $body . '</body>
    </html>';
}
