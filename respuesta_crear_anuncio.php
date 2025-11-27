<?php
require_once 'include/sesion.php';
require_once 'include/db_connect.php';
require_once 'include/flashdata.inc.php';

controlar_acceso_privado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = conectar_bd();

    $titulo = trim($_POST['titulo'] ?? '');
    $texto = trim($_POST['descripcion'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');
    $precio = (float)($_POST['precio'] ?? 0);
    $tanuncio = (int)$_POST['tanuncio'];
    $tvivienda = (int)$_POST['tvivienda'];
    $pais = (int)$_POST['pais'];
    $usuario = $_SESSION['id_usuario'];

    // Validaciones básicas según PDF [cite: 87]
    if (empty($titulo) || empty($texto)) {
        set_flashdata('error', "El título y la descripción son obligatorios.");
        header("Location: crear_anuncio.php");
        exit();
    }

    $sql = "INSERT INTO anuncios (Titulo, Texto, Ciudad, Precio, TAnuncio, TVivienda, Pais, Usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssdiiii", $titulo, $texto, $ciudad, $precio, $tanuncio, $tvivienda, $pais, $usuario);

    if ($stmt->execute()) {
        $id_anuncio = $mysqli->insert_id; // Obtener ID generado [cite: 583]
        set_flashdata('success', "Anuncio creado. Ahora añade la primera foto.");
        // Redirección obligatoria a añadir foto 
        header("Location: anyadir_foto.php?anuncio_id=" . $id_anuncio);
    } else {
        set_flashdata('error', "Error al crear anuncio: " . $stmt->error);
        header("Location: crear_anuncio.php");
    }
    
    $stmt->close();
    $mysqli->close();
    exit();
} else {
    header("Location: crear_anuncio.php");
    exit();
}
?>