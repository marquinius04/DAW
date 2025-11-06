<?php
// 1. Cargar el gestor de sesión 
require_once 'include/sesion.php';

// 2. Borrar todas las variables de sesión
$_SESSION = array();

// 3. Borrar la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finalmente, destruir la sesión
session_destroy();

// 5. Borrar las cookies de "Recordarme" al hacer logout
$expira_pasado = time() - 3600; // 1 hora en el pasado
setcookie('recordar_usuario', '', $expira_pasado, '/', '', false, true);
setcookie('recordar_clave', '', $expira_pasado, '/', '', false, true);
setcookie('ultima_visita_real', '', $expira_pasado, '/', '', false, true);
setcookie('anuncios_visitados', '', $expira_pasado, '/', '', false, true); // También borramos los visitados

// 6. Redirigir al inicio público
header("Location: index.php");
exit();
?>