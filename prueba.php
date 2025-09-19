<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Dinámica con PHP Extendido</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        footer { margin-top: 30px; font-size: 0.9em; color: #666; }
    </style>
</head>
<body>

    <?php
    $archivo_contenido = 'data/info.txt';
    $contenido = [];

    if (file_exists($archivo_contenido) && is_readable($archivo_contenido)) {
        $lineas = file($archivo_contenido, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lineas as $linea) {
            $partes = explode(':', $linea, 2);
            if (count($partes) == 2) {
                $clave = trim($partes[0]);
                $valor = trim($partes[1]);
                $contenido[$clave] = $valor;
            }
        }
    } else {
        echo '<p>Error: No se pudo cargar el contenido dinámico. Asegúrate de que "contenido.txt" exista y sea legible.</p>';
    }
    ?>

    <h1><?php echo $contenido['titulo_pag1'] ?? 'Título por defecto'; ?></h1>

    <p><?php echo $contenido['parrafo_uno'] ?? 'Este es un párrafo de ejemplo si no se carga el contenido.'; ?></p>

    <p><?php echo $contenido['parrafo_dos'] ?? 'Otro párrafo de ejemplo aquí.'; ?></p>

     <p><?php echo $contenido['parrafo_tres'] ?? 'Otro párrafo de ejemplo aquí.'; ?></p>


    <footer>
        <p><?php echo $contenido['footer_texto'] ?? 'Texto de pie de página por defecto.'; ?></p>
    </footer>

</body>
</html>