<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// --- LÓGICA PARA LEER Y CONTAR DATOS ---

// Definir las rutas a los archivos de datos.
// La ruta es '../../data/' porque el archivo gestionar.php está en 'comp/',
// y la carpeta 'data/' está dos niveles arriba.
$ruta_visitas = '../data/visitas.txt';
$ruta_clics = '../data/clics.txt';
$ruta_registros = '../data/registros_formulario.txt';

// --- Extraer el total de visitas ---
$total_visitas = 0;
if (file_exists($ruta_visitas)) {
    $lineas_visitas = file($ruta_visitas, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $total_visitas = count($lineas_visitas);
}

// --- Extraer el total de clics ---
$total_clics = 0;
if (file_exists($ruta_clics)) {
    $lineas_clics = file($ruta_clics, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas_clics as $linea) {
        $partes = explode(':', $linea);
        if (count($partes) == 2) {
            $total_clics += (int)trim($partes[1]);
        }
    }
}

// --- Extraer el total de registros del formulario ---
$total_registros = 0;
if (file_exists($ruta_registros)) {
    $contenido_registros = file_get_contents($ruta_registros);
    $total_registros = substr_count($contenido_registros, '--- Nuevo Registro ---');
}

// ------------------------------------
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
            --radius: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .app-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
            width: 100%;
            flex: 1;
        }

        /* Header */
        .app-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px;
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
        }

        .app-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary), var(--accent));
        }

        .company-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .company-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 20px;
        }

        .company-details h2 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 4px;
        }

        .company-details p {
            font-size: 14px;
            color: var(--text-light);
        }

        .logout-btn {
            background: #000000ff;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
        }

        .logout-btn:hover {
            background: #ff0000ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .logout-btn:active {
            transform: translateY(0);
        }

        /* Main Content */
        .dashboard-title {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 12px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .dashboard-subtitle {
            font-size: 18px;
            color: var(--text-light);
            margin-bottom: 40px;
            font-weight: 400;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 32px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary);
        }

        .stat-icon {
            font-size: 28px;
            margin-bottom: 16px;
            color: var(--primary);
        }

        .stat-title {
            font-size: 16px;
            color: var(--text-light);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text);
        }

        /* Quick Actions */
        .actions-section {
            background: var(--card);
            border-radius: var(--radius);
            padding: 32px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--border);
            color: var(--text);
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        .action-card {
            background: linear-gradient(135deg, #f0f9ff, #f8fafc);
            border: 2px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            text-align: center;
            transition: var(--transition);
            cursor: pointer;
        }

        .action-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
            background: linear-gradient(135deg, #dbeafe, #f0f9ff);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.15);
        }

        .action-icon {
            font-size: 36px;
            color: var(--primary);
            margin-bottom: 16px;
        }

        .action-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text);
        }

        .action-desc {
            font-size: 14px;
            color: var(--text-light);
        }

        /* Footer */
        .app-footer {
            text-align: center;
            padding: 32px;
            color: var(--text-light);
            font-size: 14px;
            margin-top: auto;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .app-header,
        .dashboard-title,
        .stat-card,
        .action-card {
            animation: fadeIn 0.6s ease-out forwards;
        }

        .stat-card:nth-child(2) { animation-delay: 0.1s; }
        .stat-card:nth-child(3) { animation-delay: 0.2s; }
        .action-card:nth-child(2) { animation-delay: 0.1s; }
        .action-card:nth-child(3) { animation-delay: 0.2s; }
        .action-card:nth-child(4) { animation-delay: 0.3s; }

        /* Responsive */
        @media (max-width: 768px) {
            .app-container {
                padding: 16px;
            }

            .app-header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .stats-grid,
            .actions-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-title {
                font-size: 28px;
            }

            .stat-card::before {
                width: 100%;
                height: 4px;
                top: 0;
                left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <header class="app-header">
            <div class="company-info">
                <div class="company-avatar">
                    <?php echo substr($_SESSION['company'], 0, 2); ?>
                </div>
                <div class="company-details">
                    <h2>Panel de Gestión</h2>
                    <p><?php echo htmlspecialchars($_SESSION['company']); ?></p>
                </div>
            </div>
            <form action="logout.php" method="post">
                <button type="submit" class="logout-btn">
                   &#8617; Cerrar Sesión
                </button>
            </form>
        </header>

        <h1 class="dashboard-title">Bienvenido al Panel de Control</h1>
        <p class="dashboard-subtitle">Gestiona todo el contenido de tu sitio desde aquí</p>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <h3 class="stat-title">Total de Visitas</h3>
                <div class="stat-value"><?php echo $total_visitas; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📝</div>
                <h3 class="stat-title">Total Interacciones</h3>
                <div class="stat-value"><?php echo $total_clics; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏱</div>
                <h3 class="stat-title">Registros y Solicitudes</h3>
                <div class="stat-value"><?php echo $total_registros; ?></div>
            </div>
        </div>

       <section class="actions-section">
    <h2 class="section-title">Acciones Rápidas</h2>
    <div class="actions-grid">
        <a href="editar_contenido.php" class="action-card">
            <div class="action-icon">✏️</div>
            <h3 class="action-title">Editar Contenido</h3>
            <p class="action-desc">Modifica textos y secciones de tu sitio.</p>
        </a>

        <a href="editar_imagenes.php" class="action-card">
            <div class="action-icon">🖼️</div>
            <h3 class="action-title">Editar Imágenes</h3>
            <p class="action-desc">Administra las imágenes de tu galería.</p>
        </a>
        
        <a href="descargar_reporte.php" class="action-card">
            <div class="action-icon">📥</div>
            <h3 class="action-title">Descargar Reporte</h3>
            <p class="action-desc">Obtén un reporte detallado de tus datos.</p>
        </a>
    </div>
</section>
    </div>

    <footer class="app-footer">
        <p>© <?php echo date('Y'); ?> <?php echo htmlspecialchars($_SESSION['company']); ?> • Sistema de Gestión de Contenido</p>
    </footer>
</body>
</html>