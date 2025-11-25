<?php
// /respuesta_login.php

// 1. INCLUSIONES ESENCIALES
require_once 'include/sesion.php'; 
require_once 'include/flashdata.inc.php'; 
require_once 'include/db_connect.php';      

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// 2. RECUPERACIÓN Y SANEAMIENTO DE DATOS
$usuario = trim($_POST['usuario'] ?? '');
$clave = $_POST['clave'] ?? ''; 
$recuerdame = isset($_POST['recuerdame']); // Booleano

// Validaciones mínimas de presencia
if (empty($usuario) || empty($clave)) {
    set_flashdata('error', 'El nombre de usuario y la contraseña son obligatorios.');
    header("Location: index.php");
    exit();
}

$mysqli = conectar_bd();
$user = null;

// 3. CONSULTA Y VERIFICACIÓN DE CONTRASEÑA (MODIFICADO)

$sql = "SELECT IdUsuario, Clave, NomUsuario, Estilo FROM usuarios WHERE NomUsuario = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $user = $resultado->fetch_assoc();
    
    // -----------------------------------------------------------------
    // MODIFICACIÓN CRUCIAL: USAR password_verify()
    // -----------------------------------------------------------------
    if (password_verify($clave, $user['Clave'])) {
        
        // 4. ÉXITO DE LOGIN: INICIAR SESIÓN

        // 4.1. Crear variables de sesión
        $_SESSION['usuario'] = $user['NomUsuario'];
        $_SESSION['id_usuario'] = $user['IdUsuario'];
        $_SESSION['estilo'] = $user['Estilo'];
        
        // 4.2. Registrar la última visita si existe la cookie (Lógica Práctica 8)
        if (isset($_COOKIE['ultima_visita_pi'])) {
             // La cookie almacena el valor de la última visita.
             $_SESSION['ultima_visita'] = $_COOKIE['ultima_visita_pi'];
        }

        // 4.3. Crear cookie "Recuérdame" si se ha marcado la opción (Lógica Práctica 8)
        if ($recuerdame) {
            $dias = 90;
            $expire = time() + ($dias * 24 * 60 * 60); 
            
            // WARNING: Por requisito de la Práctica 8, se almacena la clave en PLAIN TEXT.
            // En un entorno real, esta cookie almacenaría un token seguro, NUNCA la clave.
            setcookie('usuario_pi', $usuario, $expire);
            setcookie('clave_pi', $clave, $expire); // <-- Aquí se usa la clave en PLAIN TEXT para la cookie
        }
        
        // 4.4. Redirigir al menú privado
        set_flashdata('success', "Bienvenido, {$user['NomUsuario']}.");
        header("Location: index_logueado.php");

    } else {
        // Fallo: Contraseña incorrecta
        set_flashdata('error', 'Credenciales incorrectas. Contraseña no válida.');
        header("Location: index.php");
    }
} else {
    // Fallo: Usuario no encontrado
    set_flashdata('error', 'Credenciales incorrectas. Usuario no existe.');
    header("Location: index.php");
}

$stmt->close();
$mysqli->close();
exit();