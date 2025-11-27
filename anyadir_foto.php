<?php
$titulo_pagina = "Añadir foto a anuncio - PI";
require_once 'include/head.php';
require_once 'include/db_connect.php';
controlar_acceso_privado();

$mysqli = conectar_bd();
$uid = $_SESSION['id_usuario'];

// Obtener id del anuncio (si viene redirigido de crear_anuncio)
$anuncio_id_fijo = (int)($_GET['anuncio_id'] ?? 0);
$nombre_anuncio_fijo = "";

// Obtener lista de anuncios del usuario para el select
$sql = "SELECT IdAnuncio, Titulo FROM anuncios WHERE Usuario = $uid";
$res = $mysqli->query($sql);

if ($anuncio_id_fijo > 0) {
    // Validar que el anuncio fijo pertenece al usuario
    $check = $mysqli->query("SELECT Titulo FROM anuncios WHERE IdAnuncio = $anuncio_id_fijo AND Usuario = $uid");
    if ($row = $check->fetch_assoc()) {
        $nombre_anuncio_fijo = $row['Titulo'];
    } else {
        $anuncio_id_fijo = 0; // No es suyo o no existe
    }
}
?>

    <h2>Añadir foto a anuncio</h2>

    <form action="respuesta_anyadir_foto.php" method="post" enctype="multipart/form-data">
        
        <label for="anuncio_seleccion">(*) Anuncio de destino:</label>
        
        <?php if ($anuncio_id_fijo > 0): ?>
            <p style="padding: 10px; background: #eee; border: 1px solid #ccc;">
                <strong><?php echo htmlspecialchars($nombre_anuncio_fijo); ?></strong>
            </p>
            <input type="hidden" name="anuncio_id" value="<?php echo $anuncio_id_fijo; ?>">
        <?php else: ?>
            <select id="anuncio_seleccion" name="anuncio_id" required>
                <option value="" disabled selected>-- Seleccione un anuncio --</option>
                <?php while ($row = $res->fetch_assoc()): ?>
                    <option value="<?php echo $row['IdAnuncio']; ?>">
                        <?php echo htmlspecialchars($row['Titulo']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        <?php endif; ?>
        
        <hr>

        <label for="foto">(*) Seleccionar archivo de foto:</label>
        <input type="file" id="foto" name="foto" accept="image/*" required>

        <label for="titulo_foto">(*) Título de la foto:</label>
        <input type="text" id="titulo_foto" name="titulo_foto" required>

        <label for="alt_text">(*) Texto alternativo (mínimo 10 caracteres):</label>
        <input type="text" id="alt_text" name="alt_text" required>
        <p class="nota">Mínimo 10 caracteres. No empiece por "foto de" o "imagen de".</p>

        <button type="submit">Subir foto</button>
    </form>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>