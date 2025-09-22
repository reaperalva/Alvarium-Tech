<?php
// Define la ruta a los archivos de datos y la carpeta de imágenes
$ruta_info = '../data/info.txt';
$ruta_imagenes_directorio = '../images/';

// Lógica para procesar el formulario de subida de imágenes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensaje_exito = "";
    $cambios_realizados = false;

    // Leer el contenido actual del archivo info.txt
    $datos_actuales = [];
    if (file_exists($ruta_info)) {
        $contenido_actual = file_get_contents($ruta_info);
        $lineas = explode("\n", $contenido_actual);
        foreach ($lineas as $linea) {
            $partes = explode(':', $linea, 2);
            if (count($partes) === 2) {
                $clave = trim($partes[0]);
                $valor = trim($partes[1]);
                $datos_actuales[$clave] = $valor;
            }
        }
    }

    // Procesar cada archivo de imagen, incluyendo el logo
    $campos_imagenes = ['logo', 'img1', 'img2', 'img3', 'img4', 'img5', 'img6'];
    $nuevos_datos = $datos_actuales;

    foreach ($campos_imagenes as $campo) {
        if (isset($_FILES[$campo]) && $_FILES[$campo]['error'] === UPLOAD_ERR_OK) {
            $nombre_temporal = $_FILES[$campo]['tmp_name'];
            $nombre_original = $_FILES[$campo]['name'];
            $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
            
            // Generar un nombre de archivo único
            $nombre_nuevo = uniqid('img_', true) . '.' . $extension;
            $destino = $ruta_imagenes_directorio . $nombre_nuevo;

            if (move_uploaded_file($nombre_temporal, $destino)) {
                // Guarda la ruta en el formato deseado
                $nuevos_datos[$campo] = 'images/' . $nombre_nuevo;
                $cambios_realizados = true;
                $mensaje_exito .= "Imagen para '" . $campo . "' guardada exitosamente.<br>";
            } else {
                $mensaje_exito .= "Error al guardar la imagen para '" . $campo . "'.<br>";
            }
        }
    }
    
    // Si se subió al menos una imagen, guardar los datos
    if ($cambios_realizados) {
        // Reconstruir el contenido y guardarlo en info.txt
        $nuevo_contenido = '';
        foreach ($nuevos_datos as $clave => $valor) {
            $nuevo_contenido .= $clave . ': ' . $valor . "\n";
        }
        file_put_contents($ruta_info, trim($nuevo_contenido));
        $mensaje_exito = "Cambios guardados. " . $mensaje_exito;
    } else {
        $mensaje_exito = "No se subieron nuevas imágenes.";
    }
}

// Lógica para mostrar el formulario con las imágenes actuales
$datos = [];
if (file_exists($ruta_info)) {
    $contenido = file_get_contents($ruta_info);
    $lineas = explode("\n", $contenido);
    foreach ($lineas as $linea) {
        $partes = explode(':', $linea, 2);
        if (count($partes) === 2) {
            $clave = trim($partes[0]);
            $valor = trim($partes[1]);
            $datos[$clave] = $valor;
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
            --secondary: #8b5cf6;
            --accent: #ec4899;
            --text: #1e293b;
            --text-light: #64748b;
            --bg: #f8fafc;
            --card: #ffffff;
            --border: #e2e8f0;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background: var(--card);
            border-radius: 8px;
            box-shadow: var(--shadow);
            padding: 30px;
        }
        h1, h2 {
            color: var(--primary-dark);
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-light);
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 6px;
            background-color: #f8fafc;
        }
        .image-preview {
            width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: contain;
            border: 1px solid var(--border);
            border-radius: 6px;
            margin-top: 10px;
        }
        .success-message {
            background-color: var(--success);
            color: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
        .btn-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }
        .btn {
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color var(--transition);
            text-align: center;
        }
        .btn-submit {
            background-color: var(--primary);
            color: white;
        }
        .btn-submit:hover {
            background-color: var(--primary-dark);
        }
        .btn-return {
            background-color: var(--text-light);
            color: white;
        }
        .btn-return:hover {
            background-color: #475569;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Imágenes</h1>

        <?php if (isset($mensaje_exito)): ?>
            <div class="success-message"><?php echo $mensaje_exito; ?></div>
        <?php endif; ?>

        <form action="editar_imagenes.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="logo">Logo del Sitio</label>
                <input type="file" id="logo" name="logo" accept="image/*">
                <?php if (isset($datos['logo']) && file_exists('../' . $datos['logo'])): ?>
                    <img src="../<?php echo htmlspecialchars($datos['logo']); ?>" class="image-preview" alt="Logo actual">
                <?php endif; ?>
            </div>

            <?php for ($i = 1; $i <= 6; $i++): ?>
                <div class="form-group">
                    <label for="img<?php echo $i; ?>">Imagen <?php echo $i; ?></label>
                    <input type="file" id="img<?php echo $i; ?>" name="img<?php echo $i; ?>" accept="image/*">
                    <?php if (isset($datos['img' . $i]) && file_exists('../' . $datos['img' . $i])): ?>
                        <img src="../<?php echo htmlspecialchars($datos['img' . $i]); ?>" class="image-preview" alt="Imagen <?php echo $i; ?> actual">
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
            
            <div class="btn-container">
                <button type="submit" class="btn btn-submit">Guardar Imágenes</button>
                <a href="gestionar.php" class="btn btn-return">Volver al Dashboard</a>
            </div>
        </form>
    </div>
</body>
</html>