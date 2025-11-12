<?php
// /respuesta_login.php

// -----------------------------------------------------------
// 1. INCLUDES ESENCIALES
// -----------------------------------------------------------
// Inicia/reanuda la sesión (siempre lo primero)
require_once 'include/sesion.php'; 
// Permite la conexión a la Base de Datos
require_once 'include/db_connect.php'; 
// Permite usar set_flashdata() para mensajes de error
require_once 'include/flashdata.inc.php'; 

// Las redirecciones deben ejecutarse antes de que cargue el html
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Recoge y sanea los datos de acceso
    $usuario_ingresado = trim($_POST['usuario'] ?? '');
    $clave_ingresada = $_POST['clave'] ?? ''; 
    $recordarme = isset($_POST['recordarme']);

    // --- Validación de campos vacíos ---
    if (empty($usuario_ingresado) || empty($clave_ingresada)) {
        // Usa set_flashdata() en lugar de $_SESSION['flash_error']
        set_flashdata('error', "El usuario y la contraseña no pueden estar vacíos");
        header("Location: index.php");
        exit();
    }
    
    // -----------------------------------------------------------
    // 2. AUTENTICACIÓN CONTRA LA BASE DE DATOS (mysqli)
    // -----------------------------------------------------------
    $mysqli = conectar_bd();

    // Consulta que valida las credenciales Y obtiene el fichero CSS del usuario
    $sql = "
        SELECT U.NomUsuario, U.Clave, E.Fichero AS EstiloFichero 
        FROM USUARIOS U
        JOIN ESTILOS E ON U.Estilo = E.IdEstilo
        WHERE U.NomUsuario = ?
    ";
    
    $stmt = $mysqli->prepare($sql);

    if ($stmt === false) {
        set_flashdata('error', 'Error interno del servidor al verificar credenciales.');
        header('Location: index.php');
        $mysqli->close();
        exit;
    }
    
    // Vinculación de parámetros (s = string)
    $stmt->bind_param("s", $usuario_ingresado);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario_db = $resultado->fetch_assoc();
    
    $autenticado = false;
    $estilo_fichero = 'css/styles.css'; // Estilo por defecto

    // Comprobación de credenciales (asumiendo clave en texto plano en la BD)
    if ($usuario_db && $usuario_db['Clave'] === $clave_ingresada) {
        $autenticado = true;
        $estilo_fichero = $usuario_db['EstiloFichero'];
    }

    $stmt->close();
    $mysqli->close();
    
    // -----------------------------------------------------------
    // 3. MANEJO DE ÉXITO O FALLO
    // -----------------------------------------------------------
    
    if ($autenticado) {
        
        // --- Login exitoso: establecer sesión y cookies ---
        
        // Guarda el usuario en la sesión
        $_SESSION['usuario'] = $usuario_ingresado;
        
        // Asigna el estilo obtenido de la BD
        $_SESSION['estilo_css'] = $estilo_fichero;
        
        // Gestiona la cookie "recordarme"
        if ($recordarme) {
            // Establece parámetros de cookie (90 días)
            $expiracion = time() + (90 * 24 * 60 * 60);
            $path = '/';
            $domain = '';
            $secure = false;
            $httponly = true; 
            
            // Establece las cookies de auto-login (nombre y clave)
            setcookie('recordar_usuario', $usuario_ingresado, $expiracion, $path, $domain, $secure, $httponly);
            setcookie('recordar_clave', $clave_ingresada, $expiracion, $path, $domain, $secure, $httponly);
            
            // Guarda la cookie de "última visita real"
            setcookie('ultima_visita_real', date('d/m/Y \a \l\a\s H:i:s'), $expiracion, $path, $domain, $secure, $httponly);
            
            // Borra la variable de sesión para que sesion.php use la cookie en el próximo acceso
            unset($_SESSION['ultima_visita']);

        } else {
            // Si el usuario no marca "recordarme", borra las cookies antiguas
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
        set_flashdata('error', "Usuario o contraseña incorrectos");
        header("Location: index.php");
        exit();
    }
} else {
    // Acceso directo sin post: redirige al inicio
    header("Location: index.php");
    exit();
}
?>