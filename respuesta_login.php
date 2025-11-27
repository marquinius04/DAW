<?php

// INCLUSIONES ESENCIALES
require_once 'include/sesion.php'; 
require_once 'include/flashdata.inc.php'; 
require_once 'include/db_connect.php';      

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// RECUPERACIÓN Y SANEAMIENTO DE DATOS
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

// CONSULTA Y VERIFICACIÓN DE CONTRASEÑA

$sql = "SELECT IdUsuario, Clave, NomUsuario, Estilo FROM usuarios WHERE NomUsuario = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $user = $resultado->fetch_assoc();
    
    if (password_verify($clave, $user['Clave'])) {


        // Crear variables de sesión
        $_SESSION['usuario'] = $user['NomUsuario'];
        $_SESSION['id_usuario'] = $user['IdUsuario'];
        $_SESSION['estilo'] = $user['Estilo'];
        
        // Registrar la última visita si existe la cookie 
        if (isset($_COOKIE['ultima_visita_pi'])) {
             // La cookie almacena el valor de la última visita.
             $_SESSION['ultima_visita'] = $_COOKIE['ultima_visita_pi'];
        }

        // Crear cookie "Recuérdame" si se ha marcado la opción 
        if ($recuerdame) {
            $dias = 90;
            $expire = time() + ($dias * 24 * 60 * 60); 
            
            // En un entorno real, esta cookie almacenaría un token seguro, NUNCA la clave.
            setcookie('usuario_pi', $usuario, $expire);
            setcookie('clave_pi', $clave, $expire); 
        }
        
        // Redirigir al menú privado
        set_flashdata('success', "Bienvenido, {$user['NomUsuario']}.");
        header("Location: index_logueado.php");

    } else {
        set_flashdata('error', 'Credenciales incorrectas. Contraseña no válida.');
        header("Location: index.php");
    }
} else {
    set_flashdata('error', 'Credenciales incorrectas. Usuario no existe.');
    header("Location: index.php");
}

$stmt->close();
$mysqli->close();
exit();