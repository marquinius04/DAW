<?php
require_once 'include/sesion.php';
require_once 'include/db_connect.php';
require_once 'include/flashdata.inc.php';

controlar_acceso_privado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = conectar_bd();
    $id_anuncio = (int)$_POST['id_anuncio'];
    $uid = $_SESSION['id_usuario'];

    // 1. Verificar permisos
    $check = $mysqli->prepare("SELECT IdAnuncio FROM anuncios WHERE IdAnuncio = ? AND Usuario = ?");
    $check->bind_param("ii", $id_anuncio, $uid);
    $check->execute();
    if ($check->get_result()->num_rows === 0) die("Error de permisos.");
    $check->close();

    // 2. BORRADO FÍSICO DE FOTOS ASOCIADAS (Requisito PDF)
    $res_fotos = $mysqli->query("SELECT Foto FROM fotos WHERE Anuncio = $id_anuncio");
    while ($row = $res_fotos->fetch_assoc()) {
        $ruta = $row['Foto'];
        if (!empty($ruta) && file_exists(__DIR__ . '/' . $ruta)) {
            unlink(__DIR__ . '/' . $ruta);
        }
    }
    
    // También debemos borrar la Foto Principal si está guardada en el anuncio y no es la default
    $res_main = $mysqli->query("SELECT FPrincipal FROM anuncios WHERE IdAnuncio = $id_anuncio");
    $row_main = $res_main->fetch_assoc();
    if ($row_main && $row_main['FPrincipal'] && $row_main['FPrincipal'] !== 'img/default.jpg') {
        if (file_exists(__DIR__ . '/' . $row_main['FPrincipal'])) {
            unlink(__DIR__ . '/' . $row_main['FPrincipal']);
        }
    }

    // 3. Borrar registros en BD (Cascada lógica)
    $mysqli->query("DELETE FROM fotos WHERE Anuncio = $id_anuncio");
    $mysqli->query("DELETE FROM mensajes WHERE Anuncio = $id_anuncio");
    $mysqli->query("DELETE FROM solicitudes WHERE Anuncio = $id_anuncio"); // Por si acaso hay solicitudes

    // 4. Borrar anuncio
    $stmt = $mysqli->prepare("DELETE FROM anuncios WHERE IdAnuncio = ?");
    $stmt->bind_param("i", $id_anuncio);
    
    if ($stmt->execute()) {
        set_flashdata('success', "Anuncio eliminado y todos sus archivos borrados.");
    } else {
        set_flashdata('error', "Error al eliminar anuncio.");
    }

    $stmt->close();
    $mysqli->close();
    header("Location: mis_anuncios.php");
    exit();
}
?>