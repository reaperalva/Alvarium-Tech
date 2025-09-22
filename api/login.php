<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Autenticación</title>
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-hover: #2563eb;
            --text-color: #1f2937;
            --bg-color: #f9fafb;
            --card-bg: #ffffff;
            --error-color: #ef4444;
            --success-color: #10b981;
            --border-radius: 12px;
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: var(--bg-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), #8b5cf6, #ec4899);
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
            transition: var(--transition);
        }

        .logo-placeholder {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 24px;
        }

        .company-name {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-color);
            letter-spacing: -0.5px;
        }

        h1 {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--text-color);
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-color);
            transition: var(--transition);
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
            background: #f9fafb;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 10px;
        }

        .btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn:active {
            transform: translateY(0);
        }

        .message {
            padding: 12px;
            border-radius: var(--border-radius);
            margin: 20px 0;
            text-align: center;
            font-weight: 500;
            display: none;
        }

        .message.error {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error-color);
            border: 1px solid var(--error-color);
        }

        .message.success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }

        .loading {
            display: none;
            text-align: center;
            margin: 20px 0;
        }

        .spinner {
            width: 24px;
            height: 24px;
            border: 3px solid rgba(59, 130, 246, 0.3);
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 24px;
            }
            
            .logo-placeholder {
                width: 70px;
                height: 70px;
                font-size: 20px;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-container {
            animation: fadeInUp 0.6s ease-out;
        }

        .form-control {
            animation: fadeInUp 0.4s ease-out;
        }

        .form-control:nth-child(2) {
            animation-delay: 0.1s;
        }

        .form-control:nth-child(3) {
            animation-delay: 0.2s;
        }

        .btn {
            animation: fadeInUp 0.4s ease-out;
            animation-delay: 0.3s;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <div class="logo-placeholder" id="company-logo">LOGO</div>
            <div class="company-name" id="company-name">Nombre de la Empresa</div>
        </div>
        
        <h1>Iniciar Sesión</h1>
        
        <div class="message error" id="error-message"></div>
        <div class="message success" id="success-message"></div>
        <div class="loading" id="loading">
            <div class="spinner"></div>
        </div>
        
        <form id="login-form">
            <div class="form-group">
                <label for="company">Empresa</label>
                <input type="text" id="company" name="company" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn">Ingresar</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar datos de la empresa desde el archivo PHP
            fetch('comp/auth.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_company_info'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('company-name').textContent = data.company_name || 'Nombre de la Empresa';
                    
                    if (data.logo) {
                        const logoContainer = document.getElementById('company-logo');
                        if (data.logo.startsWith('http') || data.logo.startsWith('image')) {
                            logoContainer.innerHTML = `<img src="${data.logo}" alt="Logo" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">`;
                        } else if (data.logo === 'default') {
                            logoContainer.textContent = data.company_name.substring(0, 2).toUpperCase();
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error al cargar información de la empresa:', error);
            });

            const form = document.getElementById('login-form');
            const errorMessage = document.getElementById('error-message');
            const successMessage = document.getElementById('success-message');
            const loading = document.getElementById('loading');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                errorMessage.style.display = 'none';
                successMessage.style.display = 'none';
                loading.style.display = 'block';
                
                const company = document.getElementById('company').value.trim();
                const password = document.getElementById('password').value.trim();
                
                if (!company || !password) {
                    showError('Por favor, complete todos los campos');
                    loading.style.display = 'none';
                    return;
                }
                
                fetch('comp/auth.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=login&company=${encodeURIComponent(company)}&password=${encodeURIComponent(password)}`
                })
                .then(response => response.json())
                .then(data => {
                    loading.style.display = 'none';
                    
                    if (data.success) {
                        showSuccess('Inicio de sesión exitoso');
                        setTimeout(() => {
                            window.location.href = 'comp/gestionar.php';
                        }, 1500);
                    } else {
                        showError(data.message || 'Credenciales incorrectas');
                    }
                })
                .catch(error => {
                    loading.style.display = 'none';
                    showError('Error en la conexión. Intente nuevamente.');
                    console.error('Error:', error);
                });
            });
            
            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
            }
            
            function showSuccess(message) {
                successMessage.textContent = message;
                successMessage.style.display = 'block';
            }
        });
    </script>
</body>
</html>