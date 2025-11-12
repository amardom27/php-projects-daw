<?php
const SERVIDOR_BD="localhost";
const USUARIO_BD="jose";
const CLAVE_BD="josefa";
const NOMBRE_BD="bd_foro5";

const TIEMPO_INACT=1;//Tiempo en minutos

function error_page($title, $body)
{
    $html='<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>'.$title.'</title>
    </head>
    <body>'.$body.'          
    </body>
    </html>';
    return $html;
}