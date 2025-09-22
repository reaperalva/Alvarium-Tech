<?php
// Define la ruta al archivo de comentarios
$ruta_comentarios = '../data/comentarios.txt';

// Lógica para procesar el formulario de registro de comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';
    $autor = isset($_POST['autor']) ? trim($_POST['autor']) : '';
    $mensaje_empresa = isset($_POST['mensaje_empresa']) ? trim($_POST['mensaje_empresa']) : '';

    if (!empty($comentario) && !empty($autor) && !empty($mensaje_empresa)) {
        // Formatear el nuevo comentario para el archivo de texto
        $nuevo_comentario = "--- Nuevo Comentario ---\n" .
                           "comentario: " . $comentario . "\n" .
                           "autor: " . $autor . "\n" .
                           "mensaje_empresa: " . $mensaje_empresa . "\n";
        
        // Agregar el nuevo comentario al final del archivo
        file_put_contents($ruta_comentarios, $nuevo_comentario, FILE_APPEND);
        $mensaje_exito = "Comentario guardado exitosamente.";
    } else {
        $mensaje_error = "Por favor, completa todos los campos del formulario.";
    }
}

// Define la ruta al archivo de comentarios
$ruta_comentarios = 'data/comentarios.txt';

$comentarios = [];
$mensaje_empresa_comentarios = ''; // Variable para el mensaje de la empresa

if (file_exists($ruta_comentarios)) {
    $contenido = file_get_contents($ruta_comentarios);
    
    // Separar los registros de comentarios
    $registros = explode('--- Nuevo Comentario ---', $contenido);
    array_shift($registros); // Eliminar el primer elemento vacío
    
    foreach ($registros as $registro) {
        $lineas = explode("\n", trim($registro));
        $comentario = [];
        foreach ($lineas as $linea) {
            $partes = explode(':', $linea, 2);
            if (count($partes) === 2) {
                $clave = trim($partes[0]);
                $valor = trim($partes[1]);
                $comentario[$clave] = $valor;
            }
        }
        if (!empty($comentario)) {
            $comentarios[] = $comentario;
        }
    }
}

// Extraer el mensaje de la empresa del primer comentario si existe
if (!empty($comentarios) && isset($comentarios[0]['mensaje_empresa'])) {
    $mensaje_empresa_comentarios = $comentarios[0]['mensaje_empresa'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alvarium Tech</title>
    <link rel="icon" type="image/png" href="../img/logo2.png">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --text: #1e293b;
            --text-light: #64748b;
            --bg: #f8fafc;
            --card: #ffffff;
            --border: #e2e8f0;
            --success: #10b981;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        body { font-family: sans-serif; background-color: var(--bg); color: var(--text); padding: 20px; }
        .container { max-width: 800px; margin: auto; background: var(--card); padding: 30px; border-radius: 8px; box-shadow: var(--shadow); }
        h1 { text-align: center; color: var(--primary-dark); margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-light); }
        input[type="text"], textarea { width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 1rem; background-color: #f8fafc; }
        textarea { resize: vertical; height: 120px; }
        .success-message, .error-message { padding: 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: 500; }
        .success-message { background-color: var(--success); color: white; }
        .error-message { background-color: #fca5a5; color: #7f1d1d; }
        .btn-container { display: flex; justify-content: center; gap: 15px; margin-top: 30px; }
        .btn { padding: 12px 24px; font-size: 1rem; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; transition: background-color var(--transition); text-align: center; }
        .btn-submit { background-color: var(--primary); color: white; }
        .btn-submit:hover { background-color: var(--primary-dark); }
        .btn-return { background-color: var(--text-light); color: white; }
        .btn-return:hover { background-color: #475569; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agregar Comentario</h1>

        <?php if (isset($mensaje_exito)): ?>
            <div class="success-message"><?php echo $mensaje_exito; ?></div>
        <?php endif; ?>
        <?php if (isset($mensaje_error)): ?>
            <div class="error-message"><?php echo $mensaje_error; ?></div>
        <?php endif; ?>

        <form action="agregar_comentario.php" method="POST">
            <div class="form-group">
                <label for="comentario">Comentario del Cliente</label>
                <textarea id="comentario" name="comentario" placeholder="Ej: Una experiencia visual impresionante..." required></textarea>
            </div>
            <div class="form-group">
                <label for="autor">Autor del Comentario</label>
                <input type="text" id="autor" name="autor" placeholder="Ej: - CLIENTE SATISFECHO" required>
            </div>
            <div class="form-group">
                <label for="mensaje_empresa">Mensaje de la Empresa</label>
                <input type="text" id="mensaje_empresa" name="mensaje_empresa" placeholder="Ej: ¿Listo para comenzar tu proyecto?" required>
            </div>
            
            <div class="btn-container">
                <button type="submit" class="btn btn-submit">Guardar Comentario</button>
                <a href="gestionar.php" class="btn btn-return">Volver al Dashboard</a>
            </div>
        </form>
    </div>
</body>
</html>