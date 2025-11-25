<?php
// /respuesta_registro.php

// 1. INCLUSIONES ESENCIALES
require_once 'include/sesion.php'; 
require_once 'include/flashdata.inc.php'; 
require_once 'include/db_connect.php';      

$menu_tipo = 'publico'; 

// --- FUNCIONES DE VALIDACIÓN (SE MANTIENEN INTACTAS) ---
// (Tus funciones validarUsuario, validarClave, validarEmail, validarFechaNacimiento están aquí)
// ... (Tus funciones de validación originales deben estar aquí) ...

function validarUsuario($usuario) {
    if (empty($usuario)) return "El nombre de usuario es obligatorio";
    $len = strlen($usuario);
    if ($len < 3 || $len > 15) return "Longitud incorrecta (debe ser 3-15 caracteres)";
    if (!preg_match('/^[a-zA-Z0-9]+$/', $usuario)) return "Solo puede contener letras inglesas y números";
    if (preg_match('/^[0-9]/', $usuario)) return "No puede comenzar por un número";
    return "";
}

function validarClave($clave) {
    if (empty($clave)) return "Debe introducir una contraseña";
    $len = strlen($clave);
    if ($len < 6 || $len > 15) return "Longitud incorrecta (debe ser 6-15 caracteres)";
    if (preg_match('/[^a-zA-Z0-9_-]/', $clave)) return "Carácter no permitido (solo letras, números, - y _)";
    if (!preg_match('/[A-Z]/', $clave)) return "Debe contener al menos una mayúscula";
    if (!preg_match('/[a-z]/', $clave)) return "Debe contener al menos una minúscula";
    if (!preg_match('/[0-9]/', $clave)) return "Debe contener al menos un número";
    return "";
}
// ... (Otras funciones de validación) ...
// (Tu función validarEmail) ...
function validarEmail($email) {
    if (empty($email)) return "El correo electrónico es obligatorio";
    if (strlen($email) > 254) return "Email demasiado largo (máx 254)";
    $partes = explode('@', $email);
    if (count($partes) !== 2) return "El email debe tener una sola '@'";
    $local = $partes[0]; $dominio = $partes[1];
    $lenLocal = strlen($local);
    if ($lenLocal < 1 || $lenLocal > 64) return "Error en la longitud de la parte local (1-64)";
    if (str_starts_with($local, '.') || str_ends_with($local, '.')) return "La parte local no puede empezar o acabar con punto";
    if (str_contains($local, '..')) return "La parte local no puede tener dos puntos seguidos";
    if (!preg_match('/^[a-zA-Z0-9!#$%&\'*+\-\/=?^_`{|}~.]+$/', $local)) return "Carácter no permitido en la parte local";
    $lenDominio = strlen($dominio);
    if ($lenDominio < 1 || $lenDominio > 255) return "Error en la longitud del dominio (1-255)";
    $subdominios = explode('.', $dominio);
    if (empty($subdominios)) return "El dominio debe tener al menos una parte";
    foreach ($subdominios as $sub) {
        $lenSub = strlen($sub);
        if ($lenSub < 1 || $lenSub > 63) return "Error en la longitud del subdominio (1-63)";
        if (str_starts_with($sub, '-') || str_ends_with($sub, '-')) return "El subdominio no puede empezar o acabar con guion";
        if (!preg_match('/^[a-zA-Z0-9-]+$/', $sub)) return "Carácter no permitido en el subdominio (solo letras, números, -)";
    }
    return "";
}

// (Tu función validarFechaNacimiento) ...
function validarFechaNacimiento($dia, $mes, $anyo) {
    if (empty($dia) || empty($mes) || empty($anyo)) return "La fecha de nacimiento es obligatoria";
    $diaInt = (int)$dia; $mesInt = (int)$mes; $anyoInt = (int)$anyo;
    if (!checkdate($mesInt, $diaInt, $anyoInt)) return "La fecha introducida no es válida";
    try {
        $fechaNacimiento = new DateTime("{$anyo}-{$mes}-{$dia}");
        $fechaHace18Anios = new DateTime('-18 years');
        if ($fechaNacimiento > $fechaHace18Anios) {
            return "Debe ser mayor de 18 años";
        }
    } catch (Exception $e) { 
        return "Error al procesar la fecha"; 
    }
    return "";
}
// ----------------------------------------------------------------------


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Recoge y sanea todos los datos del formulario
    $usuario = trim($_POST['usuario'] ?? '');
    $clave1 = $_POST['clave'] ?? '';
    $clave2 = $_POST['clave2'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $sexo = trim($_POST['sexo'] ?? '');
    $diaNac = trim($_POST['diaNacimiento'] ?? '');
    $mesNac = trim($_POST['mesNacimiento'] ?? '');
    $anyoNac = trim($_POST['anyoNacimiento'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');
    $pais_id = (int)($_POST['pais'] ?? 0); 
    
    // Ejecuta validaciones una por una
    $error_mensaje = "";

    if (($msg = validarUsuario($usuario)) !== "") $error_mensaje = $msg;
    elseif (($msg = validarClave($clave1)) !== "") $error_mensaje = $msg;
    elseif (empty($clave2)) $error_mensaje = "Debe repetir la contraseña";
    elseif ($clave1 !== $clave2) $error_mensaje = "Las contraseñas no coinciden";
    elseif (($msg = validarEmail($email)) !== "") $error_mensaje = $msg;
    elseif (empty($sexo)) $error_mensaje = "Debe seleccionar un sexo";
    elseif (($msg = validarFechaNacimiento($diaNac, $mesNac, $anyoNac)) !== "") $error_mensaje = $msg;
    elseif ($pais_id === 0) $error_mensaje = "Debe seleccionar un país válido";
    
    // --- MANEJO DE ERRORES DE VALIDACIÓN ---
    if ($error_mensaje !== "") {
        set_flashdata('error', $error_mensaje); 
        $datos_previos = http_build_query($_POST); 
        header("Location: registro.php?{$datos_previos}");
        exit();
    }
    
    // --- LÓGICA DE ÉXITO: INSERCIÓN EN BASE DE DATOS (Práctica 9) ---
    
    $mysqli = conectar_bd();

    // 1. Mapeo y preparación de datos para la base de datos
    $foto_ruta = "img/default_user.jpg"; 
    $estilo_id = 1; // Asignamos el estilo por defecto (ID 1)

    $sexo_map = ['Hombre' => 1, 'Mujer' => 0, 'Otro' => 2]; 
    $sexo_db = $sexo_map[$sexo] ?? 2; 

    $fecha_nacimiento_db = "{$anyoNac}-{$mesNac}-{$diaNac}"; 

    // -------------------------------------------------------------
    // MODIFICACIÓN CRUCIAL: HASHING DE LA CONTRASEÑA
    // -------------------------------------------------------------
    $clave_hash = password_hash($clave1, PASSWORD_DEFAULT);
    // -------------------------------------------------------------

    // 2. Sentencia preparada para la inserción
    $sql = "
        INSERT INTO usuarios 
        (NomUsuario, Clave, Email, Sexo, FNacimiento, Ciudad, Pais, Foto, Estilo) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    
    $stmt = $mysqli->prepare($sql);

    if ($stmt === false) {
        set_flashdata('error', 'Error interno del servidor al preparar el registro.');
        $mysqli->close();
        header("Location: registro.php");
        exit();
    }

    // 3. Vinculación y ejecución
    // Usamos $clave_hash en lugar de $clave1
    $stmt->bind_param("sssissssi", 
        $usuario, 
        $clave_hash, // <-- USAMOS EL HASH AQUI
        $email, 
        $sexo_db, 
        $fecha_nacimiento_db, 
        $ciudad, 
        $pais_id, 
        $foto_ruta, 
        $estilo_id
    );

    if ($stmt->execute()) {
        // Éxito: Registro completado
        set_flashdata('success', "¡Registro completado para el usuario '{$usuario}'! Ya puedes iniciar sesión.");
        header("Location: index.php");

    } else {
        // Fallo: Manejo de errores de BD
        $error_msg = "Error desconocido al registrar. Código: {$stmt->errno}";
        
        if ($stmt->errno === 1062) { 
            $error_msg = "El nombre de usuario '{$usuario}' ya está registrado. Por favor, elige otro.";
        } 
        elseif ($stmt->errno === 1452) {
            $error_msg = "Error al asignar un país. Por favor, inténtalo de nuevo.";
        }

        set_flashdata('error', "Error al completar el registro: {$error_msg}");
        
        $datos_previos = http_build_query($_POST); 
        header("Location: registro.php?{$datos_previos}");
    }

    $stmt->close();
    $mysqli->close();
    exit();

} else {
    // Si se accede directamente sin post, redirige al formulario
    header("Location: registro.php");
    exit();
}