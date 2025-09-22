<?php
// Define la ruta al archivo de datos
$ruta_contenido = '../data/info.txt';

// Lógica para procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer el contenido actual del archivo para no perder los campos no editados
    if (file_exists($ruta_contenido)) {
        $contenido_actual = file_get_contents($ruta_contenido);
        $datos_actuales = [];
        $lineas = explode("\n", $contenido_actual);
        foreach ($lineas as $linea) {
            $partes = explode(':', $linea, 2);
            if (count($partes) === 2) {
                $clave = trim($partes[0]);
                $valor = trim($partes[1]);
                $datos_actuales[$clave] = $valor;
            }
        }
    } else {
        $datos_actuales = [];
    }

    // Actualizar los datos con la información del formulario
    $datos_a_guardar = $datos_actuales;
    // Se ha añadido el nuevo campo 'nombre_empresa' a la lista
    $campos = ['nombre_empresa', 'titulo_pag1', 'mensajepag1', 'titulo_pag2', 'mensajepag2', 'titulo_pag3', 'mensajepag3', 'texto_boton_cta', 'telefono_vendedor_whatsapp'];
    
    foreach ($campos as $campo) {
        if (isset($_POST[$campo])) {
            $nuevo_valor = trim($_POST[$campo]);
            if (!empty($nuevo_valor)) {
                $datos_a_guardar[$campo] = $nuevo_valor;
            }
        }
    }

    // Reconstruir el contenido en el formato 'clave:valor'
    $nuevo_contenido = '';
    foreach ($datos_a_guardar as $clave => $valor) {
        $nuevo_contenido .= $clave . ': ' . $valor . "\n";
    }

    // Escribir el nuevo contenido en el archivo
    file_put_contents($ruta_contenido, trim($nuevo_contenido));
    
    $mensaje_exito = "Contenido guardado exitosamente.";
}

// Lógica para mostrar el formulario con los valores actuales
$datos = [];
if (file_exists($ruta_contenido)) {
    $contenido = file_get_contents($ruta_contenido);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
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
        input[type="text"], input[type="tel"], textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 1rem;
            color: var(--text);
            background-color: #f8fafc;
            transition: border-color var(--transition);
        }
        input[type="text"]:focus, input[type="tel"]:focus, textarea:focus {
            border-color: var(--primary);
            outline: none;
        }
        textarea {
            resize: vertical;
            height: 120px;
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
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }
        .action-card {
            background-color: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            color: var(--text);
            box-shadow: var(--shadow);
            transition: transform var(--transition), box-shadow var(--transition);
        }
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .action-card h3 {
            margin: 0;
            color: var(--primary);
            font-size: 1.2rem;
        }
        .action-card p {
            margin: 5px 0 0;
            color: var(--text-light);
        }
        .iti--allow-dropdown {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Contenido</h1>

        <?php if (isset($mensaje_exito)): ?>
            <div class="success-message"><?php echo $mensaje_exito; ?></div>
        <?php endif; ?>

        <form action="editar_contenido.php" method="POST">
            <div class="form-group">
                <label for="nombre_empresa">Nombre de la Empresa</label>
                <input type="text" id="nombre_empresa" name="nombre_empresa" value="<?php echo htmlspecialchars($datos['nombre_empresa'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="titulo_pag1">Título Página 1</label>
                <input type="text" id="titulo_pag1" name="titulo_pag1" value="<?php echo htmlspecialchars($datos['titulo_pag1'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="mensajepag1">Mensaje Página 1</label>
                <textarea id="mensajepag1" name="mensajepag1"><?php echo htmlspecialchars($datos['mensajepag1'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="titulo_pag2">Título Página 2</label>
                <input type="text" id="titulo_pag2" name="titulo_pag2" value="<?php echo htmlspecialchars($datos['titulo_pag2'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="mensajepag2">Mensaje Página 2</label>
                <textarea id="mensajepag2" name="mensajepag2"><?php echo htmlspecialchars($datos['mensajepag2'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="titulo_pag3">Título Página 3</label>
                <input type="text" id="titulo_pag3" name="titulo_pag3" value="<?php echo htmlspecialchars($datos['titulo_pag3'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="mensajepag3">Mensaje Página 3</label>
                <textarea id="mensajepag3" name="mensajepag3"><?php echo htmlspecialchars($datos['mensajepag3'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="texto_boton_cta">Texto del Botón de Llamada a la Acción</label>
                <input type="text" id="texto_boton_cta" name="texto_boton_cta" value="<?php echo htmlspecialchars($datos['texto_boton_cta'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="telefono_vendedor_whatsapp">Número de WhatsApp del Vendedor</label>
                <input type="tel" id="telefono_vendedor_whatsapp" name="telefono_vendedor_whatsapp" class="form-control" value="<?php echo htmlspecialchars($datos['telefono_vendedor_whatsapp'] ?? ''); ?>">
            </div>

            <div class="btn-container">
                <button type="submit" class="btn btn-submit">Guardar Cambios</button>
                <a href="gestionar.php" class="btn btn-return">Volver al Dashboard</a>
            </div>
        </form>

        <div class="actions-grid">
            <a href="agregar_servicio.php" class="action-card">
                <h3>Agregar Servicio</h3>
                <p>Añade un nuevo servicio o producto a tu sitio.</p>
            </a>
            <a href="agregar_comentario.php" class="action-card">
                <h3>Agregar Comentario</h3>
                <p>Agrega un nuevo testimonio de cliente.</p>
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInputField = document.querySelector("#telefono_vendedor_whatsapp");
            
            if (phoneInputField) {
                const phoneInput = window.intlTelInput(phoneInputField, {
                    initialCountry: "auto",
                    geoIpLookup: function(callback) {
                        fetch("https://ipapi.co/json")
                            .then(res => res.json())
                            .then(data => callback(data.country_code))
                            .catch(() => callback("ve")); // Por si falla, el país por defecto es Venezuela
                    },
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                });

                const form = phoneInputField.closest('form');
                if (form) {
                    form.addEventListener('submit', function() {
                        // Antes de enviar, asegura que el valor del input sea el número completo con el prefijo de país
                        phoneInputField.value = phoneInput.getNumber();
                    });
                }
            }
        });
    </script>
</body>
</html>