    <?php

  $archivo_contenido = 'data/info.txt';
    $info = [];

    if (file_exists($archivo_contenido) && is_readable($archivo_contenido)) {
        $lineas = file($archivo_contenido, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lineas as $linea) {
            $partes = explode(':', $linea, 2);
            if (count($partes) == 2) {
                $clave = trim($partes[0]);
                $valor = trim($partes[1]);
                $info[$clave] = $valor;
            }
        }
    } else {
        echo '<p>Error: No se pudo cargar el contenido dinámico. Asegúrate de que "contenido.txt" exista y sea legible.</p>';
    }


$archivo_visitas = 'data/visitas.txt';
$ip_usuario = $_SERVER['REMOTE_ADDR'];
$fecha = date('Y-m-d H:i:s');

// Leer las IPs existentes
$visitas = file_exists($archivo_visitas) ? file($archivo_visitas, FILE_IGNORE_NEW_LINES) : [];

// Verificar si la IP ya está registrada (para evitar duplicados en la misma sesión)
if (!in_array($ip_usuario, $visitas)) {
    // Si no está, se añade la IP y la fecha
    $datos_a_guardar = $ip_usuario . " | " . $fecha . "\n";
    file_put_contents($archivo_visitas, $datos_a_guardar, FILE_APPEND);
}


// Define la ruta al archivo de servicios
$ruta_servicios = 'data/servicios.txt';

$servicios = [];
$titulo_seccion_servicios = 'NUESTROS SERVICIOS'; 

if (file_exists($ruta_servicios)) {
    $contenido = file_get_contents($ruta_servicios);
    
   
    if (preg_match('/^titulo_seccion: (.*)$/m', $contenido, $matches)) {
        $titulo_seccion_servicios = trim($matches[1]);
    }
    

    $registros = explode('--- Nuevo Servicio ---', $contenido);
    array_shift($registros); 
    
    foreach ($registros as $registro) {
        $lineas = explode("\n", trim($registro));
        $servicio = [];
        foreach ($lineas as $linea) {
            $partes = explode(':', $linea, 2);
            if (count($partes) === 2) {
                $clave = trim($partes[0]);
                $valor = trim($partes[1]);
                $servicio[$clave] = $valor;
            }
        }
        if (!empty($servicio)) {
            $servicios[] = $servicio;
        }
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
    <title><?php echo $info['nombre_empresa'] ?? 'razon'; ?></title>
    <link rel="icon" type="image/png" href="<?php echo $info['logo'] ?? 'Logo'; ?>">
    <link rel="stylesheet" href="css/stylo.css">
   
</head>
<body>
    <!-- Logo en el encabezado - MOVIDO A LA IZQUIERDA -->
    <img src="<?php echo $info['logo'] ?? 'Logo'; ?>" alt="Logo" class="logo-header">

    <div class="container" id="galleryContainer">
        <!-- Sección 1 -->
        <div class="section active" data-index="0">
            <div class="section-content" style="background-image: url('<?php echo $info['img1'] ?? 'Título por defecto'; ?>')"></div>
            <div class="section-overlay" style="background-color: rgba(0, 0, 0, 0.4)"></div>
            <h1 class="section-title"><?php echo $info['titulo_pag1'] ?? 'Título por defecto'; ?></h1>
            <p class="section-subtitle"><?php echo $info['mensajepag1'] ?? 'Título por defecto'; ?></p>
            <a id="cta-link-1" href="#" class="section-cta"><?php echo $info['texto_boton_cta'] ?? 'Boton'; ?></a>
       
        </div>
        
        <!-- Sección 2 -->
        <div class="section next" data-index="1">
            <div class="section-content" style="background-image: url('<?php echo $info['img2'] ?? 'Título por defecto'; ?>')"></div>
            <div class="section-overlay" style="background-color: rgba(0, 0, 0, 0.4)"></div>
            <h1 class="section-title"><?php echo $info['titulo_pag2'] ?? 'Título por defecto'; ?></h1>
            <p class="section-text"><?php echo $info['mensajepag2'] ?? 'Título por defecto'; ?></p>
            <a id="cta-link-2" href="#" class="section-cta"><?php echo $info['texto_boton_cta'] ?? 'Boton'; ?></a>
        </div>
        
        <!-- Sección 3 -->
        <div class="section" data-index="2">
            <div class="section-content" style="background-image: url('<?php echo $info['img3'] ?? 'Título por defecto'; ?>')"></div>
            <div class="section-overlay" style="background-color: rgba(0, 0, 0, 0.4)"></div>
            <h1 class="section-title"><?php echo $info['titulo_pag3'] ?? 'Título por defecto'; ?></h1>
            <p class="section-text"><?php echo $info['mensajepag3'] ?? 'Título por defecto'; ?></p>
            <a id="cta-link-3" href="#" class="section-cta"><?php echo $info['texto_boton_cta'] ?? 'Boton'; ?></a>
        </div>
        
        <!-- Sección 4 -->
        <div class="section" data-index="3">
            <div class="section-content" style="background-image: url('https://images.unsplash.com/photo-1504639725590-34d0984388bd?auto=format&fit=crop&w=1200&q=80')"></div>
            <div class="section-overlay" style="background-color: rgba(0, 0, 0, 0.4)"></div>
           <h1 class="section-title"><?php echo htmlspecialchars($titulo_seccion_servicios); ?></h1>
<div class="services-container">
    <?php foreach ($servicios as $servicio): ?>
        <div class="service-card">
            <h3><?php echo htmlspecialchars($servicio['titulo'] ?? ''); ?></h3>
            <p><?php echo htmlspecialchars($servicio['descripcion'] ?? ''); ?></p>
        </div>
    <?php endforeach; ?>
</div>
              
          
        </div>
        
        <!-- Sección 5 -->
        <div class="section" data-index="4">
            <div class="section-content" style="background-color: #000"></div>
            <div class="section-overlay" style="background-color: rgba(0, 0, 0, 0.4)"></div>
          
           <div class="form-container">
    <h2>CONTÁCTANOS</h2>
    
    <form id="contact-form">
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Correo:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="whatsapp">WhatsApp:</label>
            <input type="tel" id="whatsapp" name="whatsapp" required>
        </div>
        <div class="form-group full-width">
            <label for="comments">Mensaje:</label>
            <textarea id="comments" name="comments" required></textarea>
        </div>
        <button type="submit" class="form-submit">ENVIAR</button>
    </form>
    
    <div id="form-message"></div>
</div>
        </div>
      
        
        <!-- Sección 6 -->
        <div class="section" data-index="5">
            <div class="section-content" style="background-image: url('https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=1200&q=80')"></div>
            <div class="section-overlay" style="background-color: rgba(0, 0, 0, 0.4)"></div>
            
            <div class="testimonials-container">
    <div class="testimonials-slider">
        <?php foreach ($comentarios as $index => $comentario): ?>
            <div class="testimonial <?php echo ($index === 0) ? 'active' : ''; ?>">
                <p>"<?php echo htmlspecialchars($comentario['comentario'] ?? ''); ?>"</p>
                <div class="testimonial-author"><?php echo htmlspecialchars($comentario['autor'] ?? ''); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="cta-section">
        <p><?php echo htmlspecialchars($mensaje_empresa_comentarios); ?></p>
        <a id="cta-link-4" href="#" class="section-cta"><?php echo $info['texto_boton_cta'] ?? 'Boton'; ?></a>
    </div>
            </div>
        </div>
    </div>
    
    <footer class="footer">
        <button class="nav-button" id="prevBtn" disabled>
            <div class="arrow"></div>
        </button>
        <button class="nav-button" id="nextBtn">
            <div class="arrow"></div>
        </button>
        <div class="page-indicator" id="pageIndicator">1 / 6</div> <!-- Invisible pero mantiene dimensiones -->
    </footer>

    <!-- Botón flotante de WhatsApp -->
<?php
$numero_whatsapp = urlencode($info['telefono_vendedor_whatsapp'] ?? 'No Definido');
$mensaje_whatsapp = urlencode("Me da más información por favor");
$whatsapp_url = "https://wa.me/{$numero_whatsapp}?text={$mensaje_whatsapp}";

?>
<a href="<?php echo htmlspecialchars($whatsapp_url); ?>" target="_blank" class="whatsapp-button">
    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
</a>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuración
            const config = {
                animationDuration: parseInt(getComputedStyle(document.documentElement)
                    .getPropertyValue('--animation-duration')),
                totalSections: 6,
                currentIndex: 0,
                isAnimating: false,
                animationQueue: [],
                currentTestimonial: 0
            };
            
            // Elementos del DOM
            const DOM = {
                container: document.getElementById('galleryContainer'),
                sections: document.querySelectorAll('.section'),
                prevBtn: document.getElementById('prevBtn'),
                nextBtn: document.getElementById('nextBtn'),
                pageIndicator: document.getElementById('pageIndicator'),
                testimonials: document.querySelectorAll('.testimonial')
            };
            
            // Inicialización
            function init() {
                setupEventListeners();
                updateNavigation();
                
                // Precarga de imágenes para mejor rendimiento
                preloadImages();
                
                // Iniciar slider de testimonios
                setInterval(rotateTestimonials, 5000);
            }
            
            // Rotar testimonios
            function rotateTestimonials() {
                DOM.testimonials[config.currentTestimonial].classList.remove('active');
                config.currentTestimonial = (config.currentTestimonial + 1) % DOM.testimonials.length;
                DOM.testimonials[config.currentTestimonial].classList.add('active');
            }
            
            // Precargar imágenes
            function preloadImages() {
                const images = [
                    'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1504639725590-34d0984388bd?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=1200&q=80'
                ];
                
                images.forEach(src => {
                    const img = new Image();
                    img.src = src;
                });
            }
            
            // Navegar a una sección
            function goToSection(newIndex) {
                if(config.isAnimating || newIndex < 0 || newIndex >= config.totalSections) {
                    return;
                }
                
                // Agregar a la cola de animaciones
                config.animationQueue.push(() => {
                    performAnimation(newIndex);
                });
                
                // Procesar cola si no hay animación en curso
                if(!config.isAnimating) {
                    processAnimationQueue();
                }
            }
            
            // Procesar cola de animaciones
            function processAnimationQueue() {
                if(config.animationQueue.length === 0) return;
                
                const animationFunc = config.animationQueue.shift();
                animationFunc();
            }
            
            // Realizar la animación
            function performAnimation(newIndex) {
                config.isAnimating = true;
                const direction = newIndex > config.currentIndex ? 1 : -1;
                
                // Actualizar clases para animación
                DOM.sections[config.currentIndex].classList.remove('active');
                DOM.sections[newIndex].classList.add('active');
                
                // Configurar estados prev/next
                DOM.sections.forEach((section, index) => {
                    section.classList.remove('prev', 'next');
                    
                    if(index === newIndex - 1) {
                        section.classList.add('prev');
                    } else if(index === newIndex + 1) {
                        section.classList.add('next');
                    }
                });
                
                // Actualizar después de la animación
                setTimeout(() => {
                    config.currentIndex = newIndex;
                    config.isAnimating = false;
                    updateNavigation();
                    
                    // Procesar siguiente animación en cola
                    processAnimationQueue();
                }, config.animationDuration);
            }
            
            // Actualizar controles de navegación
            function updateNavigation() {
                DOM.pageIndicator.textContent = `${config.currentIndex + 1} / ${config.totalSections}`;
                DOM.prevBtn.disabled = config.currentIndex === 0;
                DOM.nextBtn.disabled = config.currentIndex === config.totalSections - 1;
            }
            
            // Configurar event listeners
            function setupEventListeners() {
                DOM.prevBtn.addEventListener('click', () => {
                    goToSection(config.currentIndex - 1);
                });
                
                DOM.nextBtn.addEventListener('click', () => {
                    goToSection(config.currentIndex + 1);
                });
                
                // Navegación por teclado
                document.addEventListener('keydown', (e) => {
                    if(e.key === 'ArrowLeft') {
                        goToSection(config.currentIndex - 1);
                    } else if(e.key === 'ArrowRight') {
                        goToSection(config.currentIndex + 1);
                    }
                });
                
                // Navegación táctil (swipe)
                let touchStartX = 0;
                let touchEndX = 0;
                
                DOM.container.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });
                
                DOM.container.addEventListener('touchend', (e) => {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                }, { passive: true });
                
                function handleSwipe() {
                    const threshold = 50;
                    const diff = touchStartX - touchEndX;
                    
                    if(diff > threshold) {
                        goToSection(config.currentIndex + 1);
                    } else if(diff < -threshold) {
                        goToSection(config.currentIndex - 1);
                    }
                }
                
                // Manejar clics en enlaces a sección 5
                document.querySelectorAll('a[href="#section5"]').forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        goToSection(4); // La sección 5 tiene índice 4 (0-based)
                    });
                });
                
                // Navegación con rueda del mouse
                DOM.container.addEventListener('wheel', (e) => {
                    e.preventDefault();
                    if(config.isAnimating) return;
                    
                    if(e.deltaY > 0) {
                        // Desplazamiento hacia abajo - siguiente sección
                        goToSection(config.currentIndex + 1);
                    } else if(e.deltaY < 0) {
                        // Desplazamiento hacia arriba - sección anterior
                        goToSection(config.currentIndex - 1);
                    }
                }, { passive: false });
            }
            
            // Iniciar la aplicación
            init();
        });
    </script>

    <script>

   const botones = ['cta-link-1', 'cta-link-2', 'cta-link-3', 'cta-link-4'];

botones.forEach(idBoton => {
    const boton = document.getElementById(idBoton);
    if (boton) {
        boton.addEventListener('click', function(event) {
            event.preventDefault(); 
            fetch('comp/contar_clic.php?boton=' + idBoton)
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                })
                .catch(error => console.error('Error:', error));
        });
    }
});
    </script>

    <script>
        document.getElementById('contact-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Detiene el envío del formulario por defecto

    const form = event.target;
    const formData = new FormData(form);

    fetch('comp/registrar.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        const messageDiv = document.getElementById('form-message');
        messageDiv.textContent = data; // Muestra la respuesta del servidor
        form.reset(); // Opcional: Limpia el formulario después del envío exitoso
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('form-message').textContent = 'Ocurrió un error al enviar el formulario.';
    });
});
    </script>
</body>
</html>