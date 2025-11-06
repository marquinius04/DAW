<?php
// [MODIFICADO]
// 1. Incluimos el gestor de sesión. Esto nos da $usuarios_permitidos
require_once 'include/sesion.php'; 

// Las redirecciones deben ser lo primero en ejecutarse.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $usuario_ingresado = trim($_POST['usuario'] ?? '');
    $clave_ingresada = $_POST['clave'] ?? ''; 
    $recordarme = isset($_POST['recordarme']);

    // Validación de campos vacíos
    if (empty($usuario_ingresado) || empty($clave_ingresada)) {
        // [MODIFICADO] Usamos flashdata en lugar de GET 
        $_SESSION['flash_error'] = "El usuario y la contraseña no pueden estar vacíos.";
        header("Location: index.php");
        exit();
    }
    
    // Comprobar credenciales [cite: 88]
    if (array_key_exists($usuario_ingresado, $usuarios_permitidos) && 
        $usuarios_permitidos[$usuario_ingresado] === $clave_ingresada) {
        
        // [MODIFICADO] Éxito: Guardar datos en la SESIÓN
        // [Requisito PDF: Task 2]
        $_SESSION['usuario'] = $usuario_ingresado;
        
        // [MODIFICADO] Asignar estilo (Task 4) 
        // (Simulamos que 'user1' prefiere el modo noche, el resto el normal)
        if ($usuario_ingresado === 'user1') {
            $_SESSION['estilo_css'] = 'css/night.css';
        } else {
            $_SESSION['estilo_css'] = 'css/styles.css';
        }
        
        // [MODIFICADO] Gestionar Cookie "Recordarme" (Task 1) 
        if ($recordarme) {
            // Cookie por 90 días [cite: 60]
            $expiracion = time() + (90 * 24 * 60 * 60);
            $path = '/';
            $domain = '';
            $secure = false; // Solo true si usas HTTPS
            $httponly = true; // [cite: 802]
            
            setcookie('recordar_usuario', $usuario_ingresado, $expiracion, $path, $domain, $secure, $httponly);
            // ATENCIÓN: Guardar clave en cookie es inseguro, pero es un requisito de la práctica [cite: 59, 88]
            setcookie('recordar_clave', $clave_ingresada, $expiracion, $path, $domain, $secure, $httponly);
            
            // Guardamos la cookie de "última visita" por primera vez [cite: 78]
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
        // [MODIFICADO] Usamos flashdata en lugar de GET 
        $_SESSION['flash_error'] = "Usuario o contraseña incorrectos.";
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>