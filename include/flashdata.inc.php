<?php

function set_flashdata($key, $message) {
    if (!isset($_SESSION['flashdata'])) {
        $_SESSION['flashdata'] = [];
    }
    $_SESSION['flashdata'][$key] = $message;
}

function get_flashdata($key) {
    if (isset($_SESSION['flashdata'][$key])) {
        $message = $_SESSION['flashdata'][$key];
        unset($_SESSION['flashdata'][$key]); // ELIMINAR automáticamente una vez leído
        return $message;
    }
    return null;
}
?>