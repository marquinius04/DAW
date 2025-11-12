<?php
// /include/flashdata.inc.php

// Este módulo asume que session_start() ya fue llamada por include/sesion.php

/**
 * Almacena un mensaje flash para ser mostrado en la próxima página después de una redirección.
 * @param string $key Clave del mensaje (ej: 'error', 'success').
 * @param string $message Contenido del mensaje.
 */
function set_flashdata($key, $message) {
    if (!isset($_SESSION['flashdata'])) {
        $_SESSION['flashdata'] = [];
    }
    $_SESSION['flashdata'][$key] = $message;
}

/**
 * Recupera y elimina un mensaje flash de la sesión para que solo se muestre una vez.
 * @param string $key Clave del mensaje.
 * @return string|null Contenido del mensaje o null si no existe.
 */
function get_flashdata($key) {
    if (isset($_SESSION['flashdata'][$key])) {
        $message = $_SESSION['flashdata'][$key];
        unset($_SESSION['flashdata'][$key]); // ELIMINAR automáticamente una vez leído
        return $message;
    }
    return null;
}
?>