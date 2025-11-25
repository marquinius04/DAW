<?php
$titulo_pagina = "Perfil de Usuario - PI";
require_once 'include/head.php'; 
require_once 'include/db_connect.php';

// Validar que recibimos un ID
$id_usuario = (int)($_GET['id'] ?? 0);

if ($id_usuario === 0) {
    echo "<h2>Usuario no especificado</h2>";
    require_once 'include/footer.php';
    exit();
}

$mysqli = conectar_bd();

// Obtener datos del usuario 
$sql = "SELECT NomUsuario, FRegistro, Foto, Ciudad, Pais FROM usuarios WHERE IdUsuario = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res = $stmt->get_result();
$usuario_datos = $res->fetch_assoc();
$stmt->close();

if (!$usuario_datos) {
    echo "<h2>Usuario no encontrado</h2>";
    $mysqli->close();
    require_once 'include/footer.php';
    exit();
}

// Obtener anuncios de este usuario
$sql_ads = "SELECT IdAnuncio, Titulo, FPrincipal, Precio, Ciudad FROM anuncios WHERE Usuario = ? ORDER BY FRegistro DESC";
$stmt_ads = $mysqli->prepare($sql_ads);
$stmt_ads->bind_param("i", $id_usuario);
$stmt_ads->execute();
$res_ads = $stmt_ads->get_result();
?>

<div class="perfil-publico">
    <h2>Perfil de <?= htmlspecialchars($usuario_datos['NomUsuario']) ?></h2>
    
    <div style="display:flex; gap: 20px; align-items: center; margin-bottom: 30px; padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 8px;">
        <img src="<?= htmlspecialchars($usuario_datos['Foto'] ?? 'img/default_user.jpg') ?>" 
             alt="Foto de perfil" 
             style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid var(--color-primario);">
        
        <div>
            <p><strong>Ciudad:</strong> <?= htmlspecialchars($usuario_datos['Ciudad']) ?></p>
            <p><strong>Miembro desde:</strong> <?= date('d/m/Y', strtotime($usuario_datos['FRegistro'])) ?></p>
        </div>
    </div>

    <h3>Anuncios publicados por <?= htmlspecialchars($usuario_datos['NomUsuario']) ?></h3>
    
    <?php if ($res_ads->num_rows > 0): ?>
        <ul class="listado-anuncios" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1em; list-style: none; padding: 0;">
            <?php while ($ad = $res_ads->fetch_assoc()): ?>
                <li style="border: 1px solid #ccc; border-radius: 5px; overflow: hidden; background: white;">
                    <article>
                        <a href="aviso.php?id=<?= $ad['IdAnuncio'] ?>" style="text-decoration: none; color: inherit; display: block;">
                            <img src="<?= htmlspecialchars($ad['FPrincipal'] ?? 'img/default.jpg') ?>" alt="Foto anuncio" style="width: 100%; height: 120px; object-fit: cover;">
                            <div style="padding: 10px;">
                                <h4 style="margin: 0 0 5px; font-size: 1em; color: var(--color-primario);"><?= htmlspecialchars($ad['Titulo']) ?></h4>
                                <p style="margin: 0; font-weight: bold;"><?= number_format($ad['Precio'], 0, ',', '.') ?> €</p>
                                <p style="margin: 0; font-size: 0.8em; color: #666;"><?= htmlspecialchars($ad['Ciudad']) ?></p>
                            </div>
                        </a>
                    </article>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Este usuario no tiene anuncios activos actualmente.</p>
    <?php endif; ?>
</div>

<?php
$stmt_ads->close();
$mysqli->close();
require_once 'include/footer.php';
?>