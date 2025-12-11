<?php
require_once 'include/sesion.php';
require_once 'include/db_connect.php';
require_once 'include/flashdata.inc.php';

controlar_acceso_privado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = conectar_bd();
    $id_foto = (int)$_POST['id_foto'];
    $uid = $_SESSION['id_usuario'];

    // Verificar propiedad y obtener la ruta del fichero
    $sql = "SELECT F.Foto, F.Anuncio 
            FROM fotos F 
            JOIN anuncios A ON F.Anuncio = A.IdAnuncio 
            WHERE F.IdFoto = ? AND A.Usuario = ?";
            
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $id_foto, $uid);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($row = $res->fetch_assoc()) {
        $id_anuncio = $row['Anuncio'];
        $ruta_foto = $row['Foto'];
        
        // Borrado físico
        // Comprobamos si existe y no es una ruta por defecto 
        if (!empty($ruta_foto) && file_exists(__DIR__ . '/' . $ruta_foto)) {
            unlink(__DIR__ . '/' . $ruta_foto);
        }
        
        // Borrar registro de la BD
        $mysqli->query("DELETE FROM fotos WHERE IdFoto = $id_foto");
        
        set_flashdata('success', "Foto eliminada correctamente (archivo y datos).");
        header("Location: ver_fotos.php?id=$id_anuncio");
    } else {
        set_flashdata('error', "Error al eliminar foto o permisos insuficientes.");
        header("Location: mis_anuncios.php");
    }

    $stmt->close();
    $mysqli->close();
    exit();
}
?>