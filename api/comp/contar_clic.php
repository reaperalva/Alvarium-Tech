<?php

$archivo_clics = '../data/clics.txt';

// Leer los contadores existentes o inicializarlos si el archivo no existe
$contadores = [];
if (file_exists($archivo_clics)) {
    $lineas = file($archivo_clics, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $linea) {
        $partes = explode(':', $linea);
        if (count($partes) === 2) {
            $contadores[trim($partes[0])] = (int)trim($partes[1]);
        }
    }
}

// Inicializar contadores si no existen
$botones = ['cta-link-1', 'cta-link-2', 'cta-link-3', 'cta-link-4'];
foreach ($botones as $boton) {
    if (!isset($contadores[$boton])) {
        $contadores[$boton] = 0;
    }
}

// Obtener el ID del botón clicado desde la URL
$id_boton = $_GET['boton'] ?? '';

// Incrementar el contador del botón clicado si es válido
if (array_key_exists($id_boton, $contadores)) {
    $contadores[$id_boton]++;
    
    // Guardar los contadores actualizados en el archivo
    $contenido_a_guardar = '';
    foreach ($contadores as $clave => $valor) {
        $contenido_a_guardar .= $clave . ":" . $valor . "\n";
    }
    file_put_contents($archivo_clics, $contenido_a_guardar);

    echo "Clic en '" . $id_boton . "' registrado. Total: " . $contadores[$id_boton];
} else {
    echo "Error: Botón no reconocido.";
}

?>