<?php
require_once 'include/sesion.php';
require_once 'include/db_connect.php';
require_once 'include/flashdata.inc.php';
require_once 'include/validaciones.inc.php'; // Incluimos tu librería de validación

controlar_acceso_privado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = conectar_bd();

    $anuncio_id = (int)$_POST['anuncio_id'];
    $titulo_foto = filter_var(trim($_POST['titulo_foto']), FILTER_SANITIZE_STRING);
    $alt_text = filter_var(trim($_POST['alt_text']), FILTER_SANITIZE_STRING);
    
    // --- VALIDACIONES ---
    $error = "";
    
    // Validar propiedad del anuncio
    $check = $mysqli->query("SELECT IdAnuncio FROM anuncios WHERE IdAnuncio = $anuncio_id AND Usuario = {$_SESSION['id_usuario']}");
    if ($check->num_rows === 0) $error = "No tienes permiso sobre este anuncio.";
    
    // Validar campos vacíos
    if (empty($titulo_foto)) $error = "El título es obligatorio.";
    
    // Validar Texto Alternativo 
    if (empty($error)) {
        $msg_alt = validarTextoAlternativo($alt_text);
        if ($msg_alt !== "") $error = $msg_alt;
    }

    // Validar subida de fichero
    if (empty($error) && (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK)) {
        $error = "Error al subir el archivo.";
    }

    if ($error !== "") {
        set_flashdata('error', $error);
        header("Location: anyadir_foto.php?anuncio_id=$anuncio_id");
        exit();
    }

    // Procesar subida de fichero
    $nombre_archivo = basename($_FILES['foto']['name']);
    
    // Ruta que usaremos para la base de datos
    $ruta_destino_web = "img/" . time() . "_" . $nombre_archivo;
    
    // Ruta que usaremos para el sistema de archivos 
    $ruta_destino_filesystem = __DIR__ . "/" . $ruta_destino_web;
    
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino_filesystem)) {
        
        // INSERTAR EN BD (Usamos la ruta web/relativa)
        $sql = "INSERT INTO fotos (Titulo, Foto, Alternativo, Anuncio) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param("sssi", $titulo_foto, $ruta_destino_web, $alt_text, $anuncio_id); 
        
        if ($stmt->execute()) {
            // Actualizar foto principal del anuncio si no tiene
            $mysqli->query("UPDATE anuncios SET FPrincipal = '$ruta_destino_web' WHERE IdAnuncio = $anuncio_id AND (FPrincipal IS NULL OR FPrincipal = 'img/default.jpg')");
            
            set_flashdata('success', "Foto añadida correctamente.");
            header("Location: aviso.php?id=$anuncio_id"); 
        } else {
            set_flashdata('error', "Error BD: " . $stmt->error);
            header("Location: anyadir_foto.php?anuncio_id=$anuncio_id");
        }
        $stmt->close();
    } else {
        set_flashdata('error', "$ruta_destino_filesystem");
        header("Location: anyadir_foto.php?anuncio_id=$anuncio_id");
    }

    $mysqli->close();
    exit();
}
?>