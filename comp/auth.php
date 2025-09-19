<?php
header('Content-Type: application/json');

$archivo_contenido = '../data/contenido.txt';
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
    echo json_encode([
        'success' => false,
        'message' => 'Error: No se pudo cargar el contenido dinámico. Asegúrate de que "contenido.txt" exista y sea legible.'
    ]);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'get_company_info') {
    echo json_encode([
        'success' => true,
        'company_name' => $contenido['company'] ?? 'Empresa',
        'logo' => $contenido['logo'] ?? 'default'
    ]);
} elseif ($action === 'login') {
    $company = $_POST['company'] ?? '';
    $password = $_POST['password'] ?? '';

    if (
        isset($contenido['company']) &&
        isset($contenido['password']) &&
        $company === $contenido['company'] &&
        $password === $contenido['password']
    ) {
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['company'] = $company;

        echo json_encode(['success' => true]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Empresa o contraseña incorrecta'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Acción no válida'
    ]);
}
?>