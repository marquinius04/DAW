<?php
require_once 'include/sesion.php';
require_once 'include/db_connect.php';
require_once 'include/flashdata.inc.php';

controlar_acceso_privado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = conectar_bd();
    
    $id_anuncio = (int)$_POST['id_anuncio'];
    $titulo = filter_var(trim($_POST['titulo']), FILTER_SANITIZE_STRING);
    $texto = filter_var(trim($_POST['descripcion']), FILTER_SANITIZE_STRING);
    $ciudad = trim($_POST['ciudad']);
    $precio = (float)$_POST['precio'];
    $tanuncio = (int)$_POST['tanuncio'];
    $tvivienda = (int)$_POST['tvivienda'];
    $pais = (int)$_POST['pais'];
    $usuario = $_SESSION['id_usuario'];

    // Validar propiedad
    $check = $mysqli->query("SELECT IdAnuncio FROM anuncios WHERE IdAnuncio = $id_anuncio AND Usuario = $usuario");
    if ($check->num_rows === 0) {
        die("Acceso denegado.");
    }

    if (empty($titulo) || empty($texto)) {
        set_flashdata('error', "Título y descripción obligatorios.");
        header("Location: modificar_anuncio.php?id=$id_anuncio");
        exit();
    }

    $sql = "UPDATE anuncios SET Titulo=?, Texto=?, Ciudad=?, Precio=?, TAnuncio=?, TVivienda=?, Pais=? WHERE IdAnuncio=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssdiiii", $titulo, $texto, $ciudad, $precio, $tanuncio, $tvivienda, $pais, $id_anuncio);
    
    if ($stmt->execute()) {
        set_flashdata('success', "Anuncio modificado correctamente.");
        header("Location: mis_anuncios.php");
    } else {
        set_flashdata('error', "Error al modificar.");
        header("Location: modificar_anuncio.php?id=$id_anuncio");
    }

    $stmt->close();
    $mysqli->close();
    exit();
}
?>