<?php
// /respuesta_registro.php

// 1. INCLUSIONES ESENCIALES (El orden es CRÍTICO)
require_once 'include/sesion.php';
require_once 'include/flashdata.inc.php';
require_once 'include/db_connect.php';
require_once 'include/validaciones.inc.php'; // <-- CRÍTICO: Asegura que las funciones de validación existan

$menu_tipo = 'publico'; 

// Las funciones de validación (validarUsuario, validarClave, etc.) se asumen cargadas desde validaciones.inc.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 2. RECOGIDA Y SANEAMIENTO DE DATOS
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
    
    // 3. EJECUCIÓN DE VALIDACIONES (LADO SERVIDOR)
    $error_mensaje = "";

    // Las funciones de validación (validarUsuario, validarClave, etc.) son las que definimos en P10
    if (($msg = validarUsuario($usuario)) !== "") $error_mensaje = $msg;
    elseif (($msg = validarClave($clave1)) !== "") $error_mensaje = $msg;
    elseif (empty($clave2)) $error_mensaje = "Debe repetir la contraseña";
    elseif ($clave1 !== $clave2) $error_mensaje = "Las contraseñas no coinciden";
    elseif (($msg = validarEmail($email)) !== "") $error_mensaje = $msg;
    elseif (empty($sexo)) $error_mensaje = "Debe seleccionar un sexo";
    elseif (($msg = validarFechaNacimiento($diaNac, $mesNac, $anyoNac)) !== "") $error_mensaje = $msg;
    elseif ($pais_id === 0) $error_mensaje = "Debe seleccionar un país válido";
    
    // 4. MANEJO DE ERRORES DE VALIDACIÓN
    if ($error_mensaje !== "") {
        // Usa set_flashdata() para guardar el error
        set_flashdata('error', $error_mensaje); 
        
        // Devuelve los datos para repoblar el formulario
        $datos_previos = http_build_query($_POST); 
        
        header("Location: registro.php?{$datos_previos}");
        exit();
    }
    
    // 5. INSERCIÓN SEGURA EN BASE DE DATOS
    
    $mysqli = conectar_bd();

    $clave_hash = password_hash($clave1, PASSWORD_DEFAULT); 
    $foto_ruta = "img/default_user.jpg"; 
    $estilo_id = 1; 
    $sexo_map = ['Hombre' => 1, 'Mujer' => 0, 'Otro' => 2]; 
    $sexo_db = $sexo_map[$sexo] ?? 2; 
    $fecha_nacimiento_db = "{$anyoNac}-{$mesNac}-{$diaNac}"; 

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

    $stmt->bind_param("sssissssi", 
        $usuario, 
        $clave_hash, 
        $email, 
        $sexo_db, 
        $fecha_nacimiento_db, 
        $ciudad, 
        $pais_id, 
        $foto_ruta, 
        $estilo_id
    );

    if ($stmt->execute()) {
        // Éxito
        set_flashdata('success', "¡Registro completado para el usuario '{$usuario}'! Ya puedes iniciar sesión.");
        
        $stmt->close();
        $mysqli->close();
        header("Location: index.php");

    } else {
        // Fallo en la BD (ej. NomUsuario duplicado)
        $error_msg = "Error desconocido al registrar. Código: {$stmt->errno}";
        
        if ($stmt->errno === 1062) { // Clave duplicada (NomUsuario)
            $error_msg = "El nombre de usuario '{$usuario}' ya está registrado. Por favor, elige otro.";
        } elseif ($stmt->errno === 1452) { // Clave ajena (País o Estilo ID no existen)
            $error_msg = "Error al asignar un país o estilo. Por favor, inténtalo de nuevo.";
        }

        set_flashdata('error', "Error al completar el registro: {$error_msg}");
        
        $datos_previos = http_build_query($_POST); 
        header("Location: registro.php?{$datos_previos}");
    }

    $mysqli->close();
    exit();

} else {
    // Si se accede directamente sin post
    header("Location: registro.php");
    exit();
}
?>