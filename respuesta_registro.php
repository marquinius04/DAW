<?php
// Incluye el gestor de sesión
require_once 'include/sesion.php';

function validarUsuario($usuario) {
    // Valida que el campo no esté vacío
    if (empty($usuario)) return "El nombre de usuario es obligatorio";
    // Valida la longitud (3-15 caracteres)
    $len = strlen($usuario);
    if ($len < 3 || $len > 15) return "Longitud incorrecta (debe ser 3-15 caracteres)";
    // Valida que solo contenga caracteres alfanuméricos
    if (!preg_match('/^[a-zA-Z0-9]+$/', $usuario)) return "Solo puede contener letras inglesas y números";
    // Valida que no comience con un número
    if (preg_match('/^[0-9]/', $usuario)) return "No puede comenzar por un número";
    return "";
}

function validarClave($clave) {
    // Valida que el campo no esté vacío
    if (empty($clave)) return "Debe introducir una contraseña";
    // Valida la longitud (6-15 caracteres)
    $len = strlen($clave);
    if ($len < 6 || $len > 15) return "Longitud incorrecta (debe ser 6-15 caracteres)";
    // Valida que solo contenga los caracteres permitidos
    if (preg_match('/[^a-zA-Z0-9_-]/', $clave)) return "Carácter no permitido (solo letras, números, - y _)";
    // Valida la presencia de una mayúscula
    if (!preg_match('/[A-Z]/', $clave)) return "Debe contener al menos una mayúscula";
    // Valida la presencia de una minúscula
    if (!preg_match('/[a-z]/', $clave)) return "Debe contener al menos una minúscula";
    // Valida la presencia de un número
    if (!preg_match('/[0-9]/', $clave)) return "Debe contener al menos un número";
    return "";
}

function validarEmail($email) {
    // Valida que el campo no esté vacío
    if (empty($email)) return "El correo electrónico es obligatorio";
    // Valida la longitud máxima
    if (strlen($email) > 254) return "Email demasiado largo (máx 254)";
    // Divide el email en parte local y dominio
    $partes = explode('@', $email);
    if (count($partes) !== 2) return "El email debe tener una sola '@'";
    $local = $partes[0]; $dominio = $partes[1];
    // Valida la longitud de la parte local
    $lenLocal = strlen($local);
    if ($lenLocal < 1 || $lenLocal > 64) return "Error en la longitud de la parte local (1-64)";
    // La parte local no puede empezar o terminar con punto
    if (str_starts_with($local, '.') || str_ends_with($local, '.')) return "La parte local no puede empezar o acabar con punto";
    // La parte local no puede tener puntos seguidos
    if (str_contains($local, '..')) return "La parte local no puede tener dos puntos seguidos";
    // Valida los caracteres permitidos en la parte local
    if (!preg_match('/^[a-zA-Z0-9!#$%&\'*+\-\/=?^_`{|}~.]+$/', $local)) return "Carácter no permitido en la parte local";
    // Valida la longitud del dominio
    $lenDominio = strlen($dominio);
    if ($lenDominio < 1 || $lenDominio > 255) return "Error en la longitud del dominio (1-255)";
    // Valida cada subdominio
    $subdominios = explode('.', $dominio);
    if (empty($subdominios)) return "El dominio debe tener al menos una parte";
    foreach ($subdominios as $sub) {
        // Valida la longitud del subdominio
        $lenSub = strlen($sub);
        if ($lenSub < 1 || $lenSub > 63) return "Error en la longitud del subdominio (1-63)";
        // El subdominio no puede empezar o terminar con guion
        if (str_starts_with($sub, '-') || str_ends_with($sub, '-')) return "El subdominio no puede empezar o acabar con guion";
        // Valida los caracteres permitidos en el subdominio
        if (!preg_match('/^[a-zA-Z0-9-]+$/', $sub)) return "Carácter no permitido en el subdominio (solo letras, números, -)";
    }
    return "";
}

function validarFechaNacimiento($dia, $mes, $anyo) {
    // Valida que todos los campos de fecha estén rellenos
    if (empty($dia) || empty($mes) || empty($anyo)) return "La fecha de nacimiento es obligatoria";
    // Convierte a enteros y comprueba si la fecha es real
    $diaInt = (int)$dia; $mesInt = (int)$mes; $anyoInt = (int)$anyo;
    if (!checkdate($mesInt, $diaInt, $anyoInt)) return "La fecha introducida no es válida";
    try {
        // Crea objetos DateTime para la comparación de edad
        $fechaNacimiento = new DateTime("{$anyo}-{$mes}-{$dia}");
        // Obtiene la fecha de hace 18 años
        $fechaHace18Anios = new DateTime('-18 years');
        // Comprueba si el usuario es mayor de 18 años
        if ($fechaNacimiento > $fechaHace18Anios) {
            return "Debe ser mayor de 18 años";
        }
    } catch (Exception $e) { 
        // Captura errores si el formato de fecha no es correcto
        return "Error al procesar la fecha"; 
    }
    return "";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Recoge todos los datos del formulario
    $usuario = trim($_POST['usuario'] ?? '');
    $clave1 = $_POST['clave'] ?? '';
    $clave2 = $_POST['clave2'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $sexo = trim($_POST['sexo'] ?? '');
    $diaNac = trim($_POST['diaNacimiento'] ?? '');
    $mesNac = trim($_POST['mesNacimiento'] ?? '');
    $anyoNac = trim($_POST['anyoNacimiento'] ?? '');
    
    // Ejecuta validaciones una por una
    $error_mensaje = "";

    if (($msg = validarUsuario($usuario)) !== "") $error_mensaje = $msg;
    elseif (($msg = validarClave($clave1)) !== "") $error_mensaje = $msg;
    elseif (empty($clave2)) $error_mensaje = "Debe repetir la contraseña";
    elseif ($clave1 !== $clave2) $error_mensaje = "Las contraseñas no coinciden";
    elseif (($msg = validarEmail($email)) !== "") $error_mensaje = $msg;
    elseif (empty($sexo)) $error_mensaje = "Debe seleccionar un sexo";
    elseif (($msg = validarFechaNacimiento($diaNac, $mesNac, $anyoNac)) !== "") $error_mensaje = $msg;
    
    // Comprueba si hay errores
    if ($error_mensaje !== "") {
        // Establece el mensaje de error para mostrar en la página de registro
        $_SESSION['flash_error'] = $error_mensaje;
        
        // Devuelve los datos del formulario para repoblar los campos
        $datos_previos = http_build_query($_POST); 
        
        // Redirige al formulario de registro
        header("Location: registro.php?{$datos_previos}");
        exit();
    }
    
    // --- Lógica de éxito: mostrar resumen ---
    
    $titulo_pagina = "Registro Exitoso";
    // Incluye la plantilla después de las redirecciones
    require_once 'include/head.php'; 
    
    $nacimiento = htmlspecialchars("{$diaNac}/{$mesNac}/{$anyoNac}");
    ?>
    
    <h2><span class="icono">check_circle</span> ¡registro exitoso!</h2>
    <p>La validación se ha completado. Tus datos han sido recibidos correctamente:</p>
    
    <section class="caja-lateral" style="background-color: #d4edda; border: 1px solid #28a745; line-height: 1.8;">
        
        <h3>Datos Introducidos:</h3>
        
        <p style="margin-bottom: 0.75em;">Nombre de Usuario: <strong><?php echo htmlspecialchars($usuario); ?></strong></p>
        <p style="margin-bottom: 0.75em;">Email: <strong><?php echo htmlspecialchars($email); ?></strong></p>
        <p style="margin-bottom: 0.75em;">Fecha de Nacimiento: <strong><?php echo $nacimiento; ?></strong></p>
        <p style="margin-bottom: 0.75em;">Sexo: <strong><?php echo htmlspecialchars($sexo); ?></strong></p>
        <p style="margin-bottom: 0.75em;">Contraseña: <strong>********</strong></p>
        
    </section>

    <p>Ya puedes volver a la página principal para entrar con tu nueva cuenta.</p>
    
    <a href="index.php" style="display: inline-block; background-color: var(--color-primario); color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-top: 15px; font-weight: bold;">
        <span class="icono">login</span> Ir al Acceso
    </a>
    
    <?php
    require_once 'include/footer.php';
    exit();
} else {
    // Si se accede directamente sin post, redirige al formulario
    header("Location: registro.php");
    exit();
}
?>