<?php
// /include/validaciones.inc.php

/**
 * Valida el nombre de usuario según las reglas de la Práctica 10.
 * * Reglas: Longitud 3-15. Solo letras (inglesas) y números. No puede comenzar con un número.
 * @param string $usuario Nombre de usuario.
 * @return string Mensaje de error, o cadena vacía si es válido.
 */
function validarUsuario($usuario) {
    $usuario = trim($usuario);
    $len = mb_strlen($usuario, 'UTF-8');
    
    if (empty($usuario)) return "El nombre de usuario es obligatorio.";
    if ($len < 3 || $len > 15) return "Longitud incorrecta (debe ser 3-15 caracteres).";
    
    // El patrón asegura que el primer carácter NO sea un número
    // y que todos los siguientes sean alfanuméricos.
    if (!preg_match('/^[^0-9][a-zA-Z0-9]*$/', $usuario)) {
        if (preg_match('/^[0-9]/', $usuario)) return "No puede comenzar con un número.";
        return "Solo puede contener letras inglesas y números.";
    }
    
    return "";
}

/**
 * Valida la contraseña según las reglas de la Práctica 10.
 * * Reglas: Longitud 6-15. Caracteres permitidos: letras, números, - y _. 
 * Al menos una mayúscula, una minúscula y un número.
 * @param string $clave Contraseña.
 * @return string Mensaje de error, o cadena vacía si es válido.
 */
function validarClave($clave) {
    $len = mb_strlen($clave, 'UTF-8');
    
    if (empty($clave)) return "Debe introducir una contraseña.";
    if ($len < 6 || $len > 15) return "Longitud incorrecta (debe ser 6-15 caracteres).";
    
    // Caracteres permitidos: letras, números, - y _
    if (preg_match('/[^a-zA-Z0-9_-]/', $clave)) return "Carácter no permitido (solo letras, números, - y _).";
    
    // Requisitos de complejidad
    if (!preg_match('/[A-Z]/', $clave)) return "Debe contener al menos una mayúscula.";
    if (!preg_match('/[a-z]/', $clave)) return "Debe contener al menos una minúscula.";
    if (!preg_match('/[0-9]/', $clave)) return "Debe contener al menos un número.";
    
    return "";
}

/**
 * Valida el correo electrónico según el patrón estricto de la Práctica 10/RFC.
 * @param string $email Correo electrónico.
 * @return string Mensaje de error, o cadena vacía si es válido.
 */
function validarEmail($email) {
    if (empty($email)) return "El correo electrónico es obligatorio.";
    if (mb_strlen($email, 'UTF-8') > 254) return "Email demasiado largo (máx 254).";

    // Primera capa de validación de formato básico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return "Formato de email inválido.";

    // Replicamos la lógica estricta de estructura del PDF:
    $partes = explode('@', $email);
    $local = $partes[0]; $dominio = $partes[1];
    
    // --- Validación Parte Local (Máx 64, sin puntos dobles/extremos) ---
    if (mb_strlen($local, 'UTF-8') < 1 || mb_strlen($local, 'UTF-8') > 64) return "Error en la longitud de la parte local (1-64 caracteres).";
    if (str_starts_with($local, '.') || str_ends_with($local, '.')) return "La parte local no puede empezar o terminar con un punto.";
    if (str_contains($local, '..')) return "La parte local no puede contener dos puntos seguidos.";
    
    // --- Validación Dominio (Máx 255 y subdominios) ---
    if (mb_strlen($dominio, 'UTF-8') < 1 || mb_strlen($dominio, 'UTF-8') > 255) return "Error en la longitud total del dominio (1-255 caracteres).";
    
    $subdominios = explode('.', $dominio);
    foreach ($subdominios as $sub) {
        $sub = trim($sub);
        if (mb_strlen($sub, 'UTF-8') < 1 || mb_strlen($sub, 'UTF-8') > 63) return "Un subdominio tiene una longitud incorrecta (1-63 caracteres).";
        if (str_starts_with($sub, '-') || str_ends_with($sub, '-')) return "Un subdominio no puede empezar o terminar con guion.";
        if (!preg_match('/^[a-zA-Z0-9-]+$/', $sub)) return "Carácter no permitido en el subdominio.";
    }

    return "";
}

/**
 * Valida la fecha de nacimiento (validez y mayor de 18 años).
 * @param string $dia Día (DD).
 * @param string $mes Mes (MM).
 * @param string $anyo Año (AAAA).
 * @return string Mensaje de error, o cadena vacía si es válido.
 */
function validarFechaNacimiento($dia, $mes, $anyo) {
    if (empty($dia) || empty($mes) || empty($anyo)) return "La fecha de nacimiento es obligatoria.";
    
    $diaInt = (int)$dia; $mesInt = (int)$mes; $anyoInt = (int)$anyo;
    
    // 1. Comprobar que es una fecha real
    if (!checkdate($mesInt, $diaInt, $anyoInt)) return "La fecha introducida no es válida.";
    
    try {
        $fechaNacimiento = new DateTime("{$anyoInt}-{$mesInt}-{$diaInt}");
        $fechaHace18Anios = new DateTime('-18 years');
        
        // 2. Comprobar que el usuario sea mayor de 18 años [cite: 2780]
        if ($fechaNacimiento > $fechaHace18Anios) {
            return "Debe ser mayor de 18 años.";
        }
    } catch (Exception $e) { 
        return "Error al procesar la fecha."; 
    }
    return "";
}

/**
 * Valida el texto alternativo de una imagen (para inserción/modificación de fotos).
 * * [cite_start]Reglas: Mínimo 10 caracteres[cite: 2814]. [cite_start]No puede empezar por palabras redundantes (foto, imagen, etc.). [cite: 2824]
 * @param string $texto_alternativo Texto a validar.
 * @return string Mensaje de error, o cadena vacía si es válido.
 */
function validarTextoAlternativo($texto_alternativo) {
    $texto_alternativo = trim($texto_alternativo);
    
    if (empty($texto_alternativo)) return "El texto alternativo es obligatorio.";
    if (mb_strlen($texto_alternativo, 'UTF-8') < 10) return "Debe tener una longitud mínima de 10 caracteres.";
    
    // Palabras redundantes al inicio
    $redundantes = '/^(foto|imagen|imagen de|foto de|texto|texto de|grafico)\s/i';
    if (preg_match($redundantes, $texto_alternativo)) {
        return "El texto alternativo no debe comenzar con palabras redundantes (foto, imagen, etc.).";
    }

    return "";
}
?>