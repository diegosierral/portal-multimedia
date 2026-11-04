<?php
/**
 * @title Plantilla base del portal
 * @description Estructura general, menú y estilos del portal.
 * @author Diego Sierra Legorreta
 * @date 2026-11-06
 */

require_once 'login.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Multimedia</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
        
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #f9f9f9;
            color: #0f0f0f;
            margin: 0;
            padding: 0;
        }

        /* Barra superior */
        .navbar {
            background-color: #ffffff;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .navbar-content {
            max-width: 1000px;
            margin: 0 auto;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar h1 {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-controls {
            font-size: 14px;
            color: #606060;
        }

        .user-controls a {
            text-decoration: none;
            color: #065fd4;
            font-weight: 500;
            margin-left: 10px;
        }

        .container {
            max-width: 1000px;
            margin: 24px auto;
            padding: 0 20px;
        }

        /* Barra de navegación */
        .chips-nav {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 16px;
        }

        .chips-nav a {
            text-decoration: none;
            background-color: #f2f2f2;
            color: #0f0f0f;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .chips-nav a:hover {
            background-color: #e5e5e5;
        }

        .chips-nav a.active {
            background-color: #0f0f0f;
            color: #ffffff;
        }

        /* Plots de contenido */
        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e5e5e5;
            margin-bottom: 24px;
        }

        .card h2 {
            margin-top: 0;
            font-size: 18px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        /* Forms y botones */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #606060;
            margin-bottom: 6px;
        }

        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-family: inherit;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #065fd4;
        }

        .btn {
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 18px;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            font-family: inherit;
            width: 100%;
        }

        .btn-primary {
            background-color: #065fd4;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #cc0000;
        }
        .btn-danger:hover {
            background-color: #a30000;
        }

        /* Alertas */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
        }
        .alert-error {
            background-color: #fce8e6;
            color: #c5221f;
        }
        .alert-success {
            background-color: #e6f4ea;
            color: #137333;
        }

        /* Contenedor centralizado para Login/Registro */
        .auth-container {
            max-width: 400px;
            margin: 0 auto;
        }
        
        .auth-link {
            text-align: center;
            margin-top: 16px;
            font-size: 14px;
            color: #606060;
        }
        
        .auth-link a {
            color: #065fd4;
            text-decoration: none;
            font-weight: 500;
        }
        
        .auth-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="navbar-content">
        <h1>Portal Multimedia</h1>
        <div class="user-controls">
            <?php if(isset($_SESSION['id_usuario'])): ?>
                Hola, <b><?php echo htmlspecialchars($_SESSION['nombre_completo']); ?></b>
                <a href="?logout=1">Cerrar sesión</a>
            <?php else: ?>
                <span>Acceso al sistema</span>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container">

    <?php if (!empty($mensaje)): ?>
        <div class="alert <?php echo $tipo_alerta === 'success' ? 'alert-success' : 'alert-error'; ?>">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['id_usuario'])): ?>
        
        <!-- Menú de navegación principal -->
        <div class="chips-nav">
            <a href="index.php" class="active">Inicio</a>
            <a href="musica.php">Música</a>
            <a href="fotos.php">Fotos</a>
            <a href="videos.php">Videos</a>
        </div>

        <div class="card">
            <h2>Panel principal</h2>
            <p style="color: #606060; font-size: 14px;">Selecciona una de las categorías en la parte superior para comenzar a subir o consultar tus archivos multimedia.</p>
        </div>

    <?php else: ?>
        
        <div class="auth-container">
            <!-- Login -->
            <div class="card" id="login-card">
                <h2>Iniciar sesión</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Correo electrónico:</label>
                        <input type="email" name="correo" required>
                    </div>
                    <div class="form-group">
                        <label>Contraseña:</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary">Entrar</button>
                </form>
                <div class="auth-link">
                    ¿No tienes cuenta? <a href="#" onclick="toggleAuth('register')">Regístrate</a>
                </div>
            </div>

            <!-- Registro -->
            <div class="card" id="register-card" style="display: none;">
                <h2>Crear cuenta</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Nombre completo:</label>
                        <input type="text" name="nombre_completo" required>
                    </div>
                    <div class="form-group">
                        <label>Correo electrónico:</label>
                        <input type="email" name="correo" required>
                    </div>
                    <div class="form-group">
                        <label>Contraseña:</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" name="registro" class="btn btn-danger">Registrarse</button>
                </form>
                <div class="auth-link">
                    ¿Ya tienes cuenta? <a href="#" onclick="toggleAuth('login')">Inicia sesión</a>
                </div>
            </div>
        </div>

        <script>
            function toggleAuth(view) {
                if(view === 'register') {
                    document.getElementById('login-card').style.display = 'none';
                    document.getElementById('register-card').style.display = 'block';
                } else {
                    document.getElementById('login-card').style.display = 'block';
                    document.getElementById('register-card').style.display = 'none';
                }
            }
        </script>

    <?php endif; ?>

</div>

</body>
</html>