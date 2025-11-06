<?php
// Incluye el gestor de sesión para acceder a la variable $usuarios_permitidos
require_once 'include/sesion.php'; 

// Las redirecciones deben ejecutarse antes de que cargue el html
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Recoge y sanea los datos de acceso
    $usuario_ingresado = trim($_POST['usuario'] ?? '');
    $clave_ingresada = $_POST['clave'] ?? ''; 
    $recordarme = isset($_POST['recordarme']);

    // --- Validación de campos vacíos ---
    if (empty($usuario_ingresado) || empty($clave_ingresada)) {
        // Establece el mensaje de error y redirige al inicio
        $_SESSION['flash_error'] = "El usuario y la contraseña no pueden estar vacíos";
        header("Location: index.php");
        exit();
    }
    
    // --- Comprobar credenciales ---
    if (array_key_exists($usuario_ingresado, $usuarios_permitidos) && 
        $usuarios_permitidos[$usuario_ingresado] === $clave_ingresada) {
        
        // --- Login exitoso: establecer sesión y cookies ---
        
        // Guarda el usuario en la sesión
        $_SESSION['usuario'] = $usuario_ingresado;
        
        // Asigna el estilo guardado para este usuario
        // Asume que get_estilo_por_usuario() existe en sesion.php
        $_SESSION['estilo_css'] = get_estilo_por_usuario($usuario_ingresado);
        
        // Gestiona la cookie "recordarme"
        if ($recordarme) {
            // Establece parámetros de cookie (90 días)
            $expiracion = time() + (90 * 24 * 60 * 60);
            $path = '/';
            $domain = '';
            $secure = false;
            $httponly = true; 
            
            // Establece las cookies de auto-login
            setcookie('recordar_usuario', $usuario_ingresado, $expiracion, $path, $domain, $secure, $httponly);
            setcookie('recordar_clave', $clave_ingresada, $expiracion, $path, $domain, $secure, $httponly);
            
            // Guarda la cookie de "última visita real"
            setcookie('ultima_visita_real', date('d/m/Y \a \l\a\s H:i:s'), $expiracion, $path, $domain, $secure, $httponly);
            
            // Borra la variable de sesión para que no se muestre el mensaje de visita antigua
            unset($_SESSION['ultima_visita']);

        } else {
            // Si el usuario no marca "recordarme", borra las cookies antiguas para deshabilitar el auto-login
            $expira_pasado = time() - 3600;
            setcookie('recordar_usuario', '', $expira_pasado, '/', '', false, true);
            setcookie('recordar_clave', '', $expira_pasado, '/', '', false, true);
            setcookie('ultima_visita_real', '', $expira_pasado, '/', '', false, true);
        }
    
        // Redirección final a la página de usuario logueado
        header("Location: index_logueado.php");
        exit();
        
    } else {
        // Fallo en credenciales: establece el mensaje de error y redirige
        $_SESSION['flash_error'] = "Usuario o contraseña incorrectos";
        header("Location: index.php");
        exit();
    }
} else {
    // Acceso directo sin post: redirige al inicio
    header("Location: index.php");
    exit();
}
?>