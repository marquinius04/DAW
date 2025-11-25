<?php
$titulo_pagina = "Galería de Fotos - PI";
require_once 'include/head.php'; 
require_once 'include/db_connect.php';

$id_anuncio = (int)($_GET['id'] ?? 0); 
if ($id_anuncio === 0 && isset($_GET['anuncio_id'])) $id_anuncio = (int)$_GET['anuncio_id'];

$mysqli = conectar_bd();

// Datos básicos del anuncio
$sql_info = "SELECT Titulo FROM anuncios WHERE IdAnuncio = $id_anuncio";
$res_info = $mysqli->query($sql_info);
$anuncio = $res_info->fetch_assoc();

// Fotos secundarias
$sql_fotos = "SELECT Foto, Titulo, Alternativo FROM fotos WHERE Anuncio = $id_anuncio";
$res_fotos = $mysqli->query($sql_fotos);
$num_fotos = $res_fotos->num_rows;
?>

<h2>Galería: <?= htmlspecialchars($anuncio['Titulo'] ?? 'Anuncio') ?></h2>
<p>Total de fotos: <?= $num_fotos ?></p>

<div class="galeria" style="display: flex; flex-wrap: wrap; gap: 10px;">
    <?php if($num_fotos > 0): ?>
        <?php while($f = $res_fotos->fetch_assoc()): ?>
            <div style="border: 1px solid #ccc; padding: 5px;">
                <img src="<?= htmlspecialchars($f['Foto']) ?>" alt="<?= htmlspecialchars($f['Alternativo']) ?>" style="max-width: 300px;">
                <p><?= htmlspecialchars($f['Titulo']) ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No hay fotos adicionales para este anuncio.</p>
    <?php endif; ?>
</div>

<p><a href="aviso.php?id=<?= $id_anuncio ?>">Volver al anuncio</a></p>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>