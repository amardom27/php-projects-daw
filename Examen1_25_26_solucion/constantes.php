<?php
const SERVIDOR = "localhost";
const USUARIO = "jose";
const CLAVE = "josefa";
const NOMBRE_BD = "bd_exam_colegio";

function error_page($title, $body) {
    return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $title . '</title>
</head>
<body>' . $body . '</body>
</html>';
}
