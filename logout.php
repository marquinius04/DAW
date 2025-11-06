<?php
// Carga el gestor de sesión
require_once 'include/sesion.php';

// Borra todas las variables de sesión
$_SESSION = array();

// Borra la cookie de sesión
if (ini_get("session.use_cookies")) {
    // Obtiene los parámetros de la cookie de sesión
    $params = session_get_cookie_params();
    // Establece la cookie con una fecha de expiración pasada
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruye la sesión
session_destroy();

// Borra las cookies de "recordarme"
$expira_pasado = time() - 3600; // 1 hora en el pasado
// Borra las cookies de auto-login
setcookie('recordar_usuario', '', $expira_pasado, '/', '', false, true);
setcookie('recordar_clave', '', $expira_pasado, '/', '', false, true);
// Borra las cookies de visita y anuncios visitados
setcookie('ultima_visita_real', '', $expira_pasado, '/', '', false, true);
setcookie('anuncios_visitados', '', $expira_pasado, '/', '', false, true); 

// Redirige al inicio público
header("Location: index.php");
exit();
?>