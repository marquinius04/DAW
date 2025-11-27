<?php
require_once 'include/sesion.php';
require_once 'include/db_connect.php';
require_once 'include/flashdata.inc.php';

controlar_acceso_privado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = conectar_bd();
    $id_anuncio = (int)$_POST['id_anuncio'];
    $uid = $_SESSION['id_usuario'];

    // 1. Verificar propiedad
    $check = $mysqli->prepare("SELECT IdAnuncio FROM anuncios WHERE IdAnuncio = ? AND Usuario = ?");
    $check->bind_param("ii", $id_anuncio, $uid);
    $check->execute();
    if ($check->get_result()->num_rows === 0) die("Error de permisos.");

    // 2. Borrar fotos asociadas
    $mysqli->query("DELETE FROM fotos WHERE Anuncio = $id_anuncio");

    // 3. Borrar mensajes
    $mysqli->query("DELETE FROM mensajes WHERE Anuncio = $id_anuncio");

    // 4. Borrar anuncio
    $stmt = $mysqli->prepare("DELETE FROM anuncios WHERE IdAnuncio = ?");
    $stmt->bind_param("i", $id_anuncio);
    
    if ($stmt->execute()) {
        set_flashdata('success', "Anuncio eliminado correctamente.");
    } else {
        set_flashdata('error', "Error al eliminar anuncio.");
    }

    $mysqli->close();
    header("Location: mis_anuncios.php");
    exit();
}
?>