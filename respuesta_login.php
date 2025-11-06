<?php
// 1. Incluimos el gestor de sesión. Esto nos da $usuarios_permitidos
require_once 'include/sesion.php'; 

// Las redirecciones deben ser lo primero en ejecutarse.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $usuario_ingresado = trim($_POST['usuario'] ?? '');
    $clave_ingresada = $_POST['clave'] ?? ''; 
    $recordarme = isset($_POST['recordarme']);

    // Validación de campos vacíos
    if (empty($usuario_ingresado) || empty($clave_ingresada)) {
        $_SESSION['flash_error'] = "El usuario y la contraseña no pueden estar vacíos.";
        header("Location: index.php");
        exit();
    }
    
    // Comprobar credenciales
    if (array_key_exists($usuario_ingresado, $usuarios_permitidos) && 
        $usuarios_permitidos[$usuario_ingresado] === $clave_ingresada) {
        
        // Guardar datos en la SESIÓN
        $_SESSION['usuario'] = $usuario_ingresado;
        
        // Asignar estilo 
        // Llamamos a la función de sesion.php para obtener el estilo de este usuario
        $_SESSION['estilo_css'] = get_estilo_por_usuario($usuario_ingresado);
        
        // Gestionar cookie "Recordarme" 
        if ($recordarme) {
            // Cookie por 90 días
            $expiracion = time() + (90 * 24 * 60 * 60);
            $path = '/';
            $domain = '';
            $secure = false; // Solo true si usas HTTPS
            $httponly = true; 
            
            setcookie('recordar_usuario', $usuario_ingresado, $expiracion, $path, $domain, $secure, $httponly);
            setcookie('recordar_clave', $clave_ingresada, $expiracion, $path, $domain, $secure, $httponly);
            
            // Guardamos la cookie de "última visita" por primera vez
            setcookie('ultima_visita_real', date('d/m/Y \a \l\a\s H:i:s'), $expiracion, $path, $domain, $secure, $httponly);
            
            // Borramos la sesión de "ultima_visita" para que no se muestre en el primer login
            unset($_SESSION['ultima_visita']);

        } else {
            // Si el usuario NO marca "recordarme", nos aseguramos de borrar cookies antiguas
            $expira_pasado = time() - 3600;
            setcookie('recordar_usuario', '', $expira_pasado, '/', '', false, true);
            setcookie('recordar_clave', '', $expira_pasado, '/', '', false, true);
            setcookie('ultima_visita_real', '', $expira_pasado, '/', '', false, true);
        }
    
        header("Location: index_logueado.php");
        exit();
        
    } else {
        $_SESSION['flash_error'] = "Usuario o contraseña incorrectos.";
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>