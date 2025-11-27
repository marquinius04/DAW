<?php
$titulo_pagina = "Eliminar Anuncio - PI";
require_once 'include/head.php'; 
require_once 'include/db_connect.php';
controlar_acceso_privado();

$id_anuncio = (int)($_GET['id'] ?? 0);
$mysqli = conectar_bd();

// Verificar que el anuncio existe y es del usuario
$stmt = $mysqli->prepare("SELECT Titulo FROM anuncios WHERE IdAnuncio = ? AND Usuario = ?");
$uid = $_SESSION['id_usuario'];
$stmt->bind_param("ii", $id_anuncio, $uid);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    $titulo = $row['Titulo'];
} else {
    set_flashdata('error', "Anuncio no encontrado.");
    header("Location: mis_anuncios.php");
    exit();
}
?>

<div style="border: 2px solid red; padding: 20px; background: #fff5f5; border-radius: 8px; max-width: 600px; margin: 20px auto;">
    <h2 style="color: #c62828;">Eliminar Anuncio</h2>
    <p>¿Estás seguro de que deseas eliminar el anuncio <strong>"<?= htmlspecialchars($titulo) ?>"</strong>?</p>
    <p>Se borrarán también todas las fotos y mensajes asociados.</p>
    
    <form action="respuesta_eliminar_anuncio.php" method="post">
        <input type="hidden" name="id_anuncio" value="<?= $id_anuncio ?>">
        
        <button type="submit" style="background-color: #c62828;">Sí, eliminar definitivamente</button>
        <a href="mis_anuncios.php" class="button" style="background: #ccc; padding: 10px; border-radius: 5px; text-decoration: none; color: black;">Cancelar</a>
    </form>
</div>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>