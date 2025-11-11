<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 1</title>
</head>

<body>
    <h1>Pasar el contendio de un Textarea a "Ficheros/resultado.txt"</h1>
    <form action="ejercicio1.php" method="post">
        <textarea name="texto" id="texto" placeholder="Teclee al menos una línea" rows="10" cols="5"></textarea>
        <p>
            <button type="submit" name="btnPasar">Pasar e fichero</button>
            <button type="submit">Borrar Textarea</button>
        </p>
    </form>

</body>

</html>