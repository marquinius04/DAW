<?php
$titulo_pagina = "Eliminar Foto - PI";
require_once 'include/head.php'; 
require_once 'include/db_connect.php';
controlar_acceso_privado();

$id_foto = (int)$_GET['id'];
$mysqli = conectar_bd();
$uid = $_SESSION['id_usuario'];

// Comprobar que la foto pertenece a un anuncio del usuario
$sql = "SELECT F.IdFoto, F.Titulo, F.Foto FROM fotos F JOIN anuncios A ON F.Anuncio = A.IdAnuncio WHERE F.IdFoto = ? AND A.Usuario = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $id_foto, $uid);
$stmt->execute();
$res = $stmt->get_result();

if (!$row = $res->fetch_assoc()) {
    die("No tienes permiso o la foto no existe.");
}
?>

<div style="max-width: 500px; margin: 20px auto; text-align: center; border: 1px solid #ddd; padding: 20px;">
    <h3>Eliminar Foto</h3>
    <p>¿Deseas eliminar esta foto?</p>
    <img src="<?= htmlspecialchars($row['Foto']) ?>" style="max-width: 200px; margin: 10px 0;">
    <p><em><?= htmlspecialchars($row['Titulo']) ?></em></p>
    
    <form action="respuesta_eliminar_foto.php" method="post">
        <input type="hidden" name="id_foto" value="<?= $id_foto ?>">
        <button type="submit" style="background-color: red;">Confirmar eliminación</button>
        <button type="button" onclick="history.back()">Cancelar</button>
    </form>
</div>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>