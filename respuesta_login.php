<?php
// Fichero: respuesta_login.php (PÁGINA DE PROCESAMIENTO)

// IMPORTANTE: Asegúrate que la ruta sea correcta (datos/usuarios.php o data/usuarios.php)
require_once 'data/usuarios.php'; 

// Las redirecciones deben ser lo primero en ejecutarse.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $usuario_ingresado = trim($_POST['usuario'] ?? '');
    $clave_ingresada = $_POST['clave'] ?? ''; 

    // Validación de Campos Vacíos
    if (empty($usuario_ingresado) || empty($clave_ingresada)) {
        $error_msg = urlencode("El usuario y la contraseña no pueden estar vacíos.");
        header("Location: index.php?error={$error_msg}");
        exit();
    }
    
    // Comprobar credenciales
    if (array_key_exists($usuario_ingresado, $usuarios_permitidos) && 
        $usuarios_permitidos[$usuario_ingresado] === $clave_ingresada) {
        
        // ÉXITO: Redirección a la página de usuario registrado
        header("Location: index_logueado.php");
        exit();
        
    } else {
        // FALLO: Redirección al login con mensaje de error
        $error_msg = urlencode("Usuario o contraseña incorrectos.");
        header("Location: index.php?error={$error_msg}");
        exit();
    }
} else {
    // Si acceden directamente a esta URL sin POST, los enviamos al inicio.
    header("Location: index.php");
    exit();
}
?>