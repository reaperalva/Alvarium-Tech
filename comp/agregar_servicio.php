<?php
// Define la ruta al archivo de servicios
$ruta_servicios = '../data/servicios.txt';

// Lógica para procesar el formulario de registro de servicio
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Escapar y sanitizar los datos del formulario
    $titulo_seccion = isset($_POST['titulo_seccion']) ? trim($_POST['titulo_seccion']) : '';
    $titulo_servicio = isset($_POST['titulo_servicio']) ? trim($_POST['titulo_servicio']) : '';
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

    // Leer el contenido actual del archivo
    $contenido_actual = file_exists($ruta_servicios) ? file_get_contents($ruta_servicios) : '';

    // Si se envía un nuevo título de sección, actualizarlo.
    if (!empty($titulo_seccion)) {
        // Usar una expresión regular para encontrar y reemplazar el título de la sección
        $contenido_actual = preg_replace('/^titulo_seccion:.*/m', 'titulo_seccion: ' . $titulo_seccion, $contenido_actual, 1, $count);
        if ($count === 0) {
            // Si no se encontró, añadirlo al principio
            $contenido_actual = 'titulo_seccion: ' . $titulo_seccion . "\n" . $contenido_actual;
        }
    }

    // Si se enviaron datos del nuevo servicio, agregarlo
    if (!empty($titulo_servicio) && !empty($descripcion)) {
        $nuevo_servicio = "--- Nuevo Servicio ---\n" .
                          "titulo: " . $titulo_servicio . "\n" .
                          "descripcion: " . $descripcion . "\n";
        
        // Agregar el nuevo servicio al final del archivo
        $contenido_actual .= "\n" . $nuevo_servicio;
        
        // Guardar el contenido actualizado
        file_put_contents($ruta_servicios, $contenido_actual);
        $mensaje_exito = "Servicio y/o título de sección guardado exitosamente.";
    } else {
        $mensaje_error = "Por favor, completa los campos de 'Servicio' y 'Descripción'.";
    }
}

// Lógica para leer el título actual de la sección
$titulo_seccion_actual = '';
if (file_exists($ruta_servicios)) {
    $contenido = file_get_contents($ruta_servicios);
    $lineas = explode("\n", $contenido);
    foreach ($lineas as $linea) {
        if (strpos($linea, 'titulo_seccion:') === 0) {
            $titulo_seccion_actual = trim(substr($linea, strlen('titulo_seccion:')));
            break;
        }
    }
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
        <h1>Agregar Nuevo Servicio</h1>

        <?php if (isset($mensaje_exito)): ?>
            <div class="success-message"><?php echo $mensaje_exito; ?></div>
        <?php endif; ?>
        <?php if (isset($mensaje_error)): ?>
            <div class="error-message"><?php echo $mensaje_error; ?></div>
        <?php endif; ?>

        <form action="agregar_servicio.php" method="POST">
            <div class="form-group">
                <label for="titulo_seccion">Título de la Sección de Servicios</label>
                <input type="text" id="titulo_seccion" name="titulo_seccion" value="<?php echo htmlspecialchars($titulo_seccion_actual); ?>" placeholder="Ej: NUESTROS SERVICIOS">
            </div>

            <hr style="margin: 30px 0; border: 1px solid var(--border);">
            
            <div class="form-group">
                <label for="titulo_servicio">Título del Servicio</label>
                <input type="text" id="titulo_servicio" name="titulo_servicio" placeholder="Ej: SOLUCIONES" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción del Servicio</label>
                <textarea id="descripcion" name="descripcion" placeholder="Ej: Desarrollo de estrategias personalizadas..." required></textarea>
            </div>
            
            <div class="btn-container">
                <button type="submit" class="btn btn-submit">Guardar Servicio</button>
                <a href="gestionar.php" class="btn btn-return">Volver al Dashboard</a>
            </div>
        </form>
    </div>
</body>
</html>