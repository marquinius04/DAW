<?php
$titulo_pagina = "Galería de Fotos - PI";
require_once 'include/head.php'; 
require_once 'include/db_connect.php';

$id_anuncio = (int)($_GET['id'] ?? 0); 
if ($id_anuncio === 0 && isset($_GET['anuncio_id'])) $id_anuncio = (int)$_GET['anuncio_id'];

$mysqli = conectar_bd();

// Datos del anuncio y comprobar si el usuario es el dueño
$sql_info = "SELECT Titulo, Usuario FROM anuncios WHERE IdAnuncio = $id_anuncio";
$res_info = $mysqli->query($sql_info);
$anuncio = $res_info->fetch_assoc();

$es_propietario = (isset($_SESSION['id_usuario']) && $_SESSION['id_usuario'] == $anuncio['Usuario']);

$sql_fotos = "SELECT IdFoto, Foto, Titulo, Alternativo FROM fotos WHERE Anuncio = $id_anuncio";
$res_fotos = $mysqli->query($sql_fotos);
?>

<h2>Galería: <?= htmlspecialchars($anuncio['Titulo'] ?? 'Anuncio') ?></h2>

<div class="galeria" style="display: flex; flex-wrap: wrap; gap: 15px;">
    <?php while($f = $res_fotos->fetch_assoc()): ?>
        <div style="border: 1px solid #ccc; padding: 10px; background: white; border-radius: 5px;">
            <img src="<?= htmlspecialchars($f['Foto']) ?>" alt="<?= htmlspecialchars($f['Alternativo']) ?>" style="max-width: 250px; display:block; margin-bottom: 5px;">
            <p><strong><?= htmlspecialchars($f['Titulo']) ?></strong></p>
            
            <?php if ($es_propietario): ?>
                <div style="margin-top: 5px; border-top: 1px solid #eee; padding-top: 5px;">
                   <a href="eliminar_foto.php?id=<?= $f['IdFoto'] ?>" style="color: red; font-size: 0.9em; text-decoration: none;">
                       <span class="icono">delete</span> Eliminar foto
                   </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>

<p style="margin-top: 20px;">
    <a href="aviso.php?id=<?= $id_anuncio ?>">Volver al anuncio</a>
    <?php if ($es_propietario): ?>
        | <a href="anyadir_foto.php?anuncio_id=<?= $id_anuncio ?>">Añadir otra foto</a>
    <?php endif; ?>
</p>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>