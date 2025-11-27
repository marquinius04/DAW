<?php
require_once 'include/sesion.php';
require_once 'include/db_connect.php';
require_once 'include/flashdata.inc.php';

controlar_acceso_privado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = conectar_bd();
    $id_foto = (int)$_POST['id_foto'];
    $uid = $_SESSION['id_usuario'];

    // Verificar propiedad
    $check = $mysqli->query("SELECT F.Anuncio FROM fotos F JOIN anuncios A ON F.Anuncio = A.IdAnuncio WHERE F.IdFoto = $id_foto AND A.Usuario = $uid");
    
    if ($row = $check->fetch_assoc()) {
        $id_anuncio = $row['Anuncio'];
        
        // Borrar foto
        $mysqli->query("DELETE FROM fotos WHERE IdFoto = $id_foto");
        
        set_flashdata('success', "Foto eliminada.");
        header("Location: ver_fotos.php?id=$id_anuncio");
    } else {
        set_flashdata('error', "Error al eliminar foto.");
        header("Location: mis_anuncios.php");
    }

    $mysqli->close();
    exit();
}
?>