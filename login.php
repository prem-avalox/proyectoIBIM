<?php
session_start();
require_once 'config/conexion.php';

// Obtener mensajes de sesión y limpiarlos
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
unset($_SESSION['error']);
unset($_SESSION['success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Proceso de login
        $email = limpiar_entrada($_POST['email']);
        $password = $_POST['password'];
        
        $stmt = $conexion->prepare("SELECT id, nombre, email, password FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            if (password_verify($password, $usuario['password'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_email'] = $usuario['email'];
                
                // Actualizar último acceso
                $update = $conexion->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?");
                $update->bind_param("i", $usuario['id']);
                $update->execute();
                
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['error'] = 'Credenciales incorrectas';
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = 'Usuario no encontrado';
            header("Location: login.php");
            exit();
        }
        $stmt->close();
    } elseif (isset($_POST['register'])) {
        // Proceso de registro
        $nombre = limpiar_entrada($_POST['nombre']);
        $email = limpiar_entrada($_POST['email']);
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        
        if (empty($nombre) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            header("Location: login.php");
            exit();
        } elseif ($password !== $password_confirm) {
            $_SESSION['error'] = 'Las contraseñas no coinciden';
            header("Location: login.php");
            exit();
        } elseif (strlen($password) < 6) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
            header("Location: login.php");
            exit();
        } else {
            // Verificar si el email ya existe
            $check = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();
            
            if ($check->num_rows > 0) {
                $_SESSION['error'] = 'Este correo electrónico ya está registrado';
                header("Location: login.php");
                exit();
            } else {
                // Crear nuevo usuario
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $nombre, $email, $password_hash);
                
                if ($stmt->execute()) {
                    $_SESSION['success'] = 'Cuenta creada exitosamente. Ya puedes iniciar sesión.';
                    header("Location: login.php");
                    exit();
                } else {
                    $_SESSION['error'] = 'Error al crear la cuenta';
                    header("Location: login.php");
                    exit();
                }
                $stmt->close();
            }
            $check->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@100;200;300;400;500;600;700;800;900&family=Roboto+Condensed:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Acceso - Clothing Store</title>
</head>
<body class="general">
    <div class="header">
        <div class="filter-bar">
            <i class="far fa-bars"></i>
        </div>
        <div class="logo">
            <a href="index.php" style="text-decoration: none; color: inherit;">
                <h1>CLOTHING STORE</h1>
            </a>
        </div>
        <div class="button-container">
            <div id="search">
                <i class="far fa-search"></i>
            </div>
            <div id="user-info">
                <i class="far fa-user"></i>
            </div>
            <div id="shopping-bag">
                <i class="far fa-shopping-bag"></i>
            </div>
        </div>
    </div>
    
    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/cart-sidebar.php'; ?>
    
    <a href="index.php" class="back-button">
        <i class="fas fa-chevron-left"></i>
        <span>Volver a la tienda</span>
    </a>

    <div class="login-container">
        <div class="auth-box">
            <div class="auth-tabs">
                <button class="auth-tab active" onclick="switchTab('login')">INICIAR SESIÓN</button>
                <button class="auth-tab" onclick="switchTab('register')">CREAR CUENTA</button>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <!-- Formulario de Login -->
            <form id="login-form" class="auth-form" method="POST" style="display: block;">
                <h2>Bienvenido</h2>
                <p class="auth-subtitle">Accede a tu cuenta para una experiencia personalizada</p>
                
                <div class="form-group">
                    <label for="login-email">Correo Electrónico</label>
                    <input type="email" id="login-email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="login-password">Contraseña</label>
                    <input type="password" id="login-password" name="password" required>
                </div>
                
                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>Recordarme</span>
                    </label>
                    <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
                </div>
                
                <button type="submit" name="login" class="btn-primary">INICIAR SESIÓN</button>
            </form>
            
            <!-- Formulario de Registro -->
            <form id="register-form" class="auth-form" method="POST" style="display: none;">
                <h2>Crear Cuenta</h2>
                <p class="auth-subtitle">Únete a nuestra comunidad exclusiva</p>
                
                <div class="form-group">
                    <label for="register-nombre">Nombre Completo</label>
                    <input type="text" id="register-nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="register-email">Correo Electrónico</label>
                    <input type="email" id="register-email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="register-password">Contraseña</label>
                    <input type="password" id="register-password" name="password" required minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="register-password-confirm">Confirmar Contraseña</label>
                    <input type="password" id="register-password-confirm" name="password_confirm" required minlength="6">
                </div>
                
                <label class="checkbox-label">
                    <input type="checkbox" required>
                    <span>Acepto los <a href="#">términos y condiciones</a></span>
                </label>
                
                <button type="submit" name="register" class="btn-primary">CREAR CUENTA</button>
            </form>
        </div>
    </div>
    
    <script>
        function switchTab(tab) {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const tabs = document.querySelectorAll('.auth-tab');
            
            tabs.forEach(t => t.classList.remove('active'));
            
            if (tab === 'login') {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
                tabs[0].classList.add('active');
            } else {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                tabs[1].classList.add('active');
            }
        }
    </script>
</body>
</html>
<?php $conexion->close(); ?>
