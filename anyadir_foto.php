<?php
$titulo_pagina = "Añadir foto a anuncio - PI";
// Incluye la cabecera y la lógica del gestor de sesión
require_once 'include/head.php';
// Controla que solo usuarios logueados puedan acceder a la página
controlar_acceso_privado();

// --- Lógica de modo de formulario ---
// Obtiene id del anuncio, si es 0 es fijo, si no, es para seleccionar
$anuncio_id_fijo = (int)($_GET['anuncio_id'] ?? 0);
// Determina si el anuncio es fijo (viene de aviso.php) o necesita ser seleccionado (viene del menú)
$modo_seleccion = ($anuncio_id_fijo > 0) ? 'fijo' : 'seleccion';

// Estos datos simulan los anuncios activos del usuario
$anuncios_usuario_lista = [
    1 => 'Piso céntrico en Madrid',
    2 => 'Apartamento en Valencia',
    3 => 'Chalet en Sevilla',
];

// Si el id es fijo, busca su nombre para mostrarlo
$nombre_anuncio_fijo = $anuncios_usuario_lista[$anuncio_id_fijo] ?? "Anuncio no encontrado";
?>

    <h2>Añadir foto a anuncio</h2>

    <form action="index.php" method="post" enctype="multipart/form-data">
        
    <label for="anuncio_seleccion">(*) Anuncio de destino:</label>
        
        <?php if ($modo_seleccion === 'fijo'): ?>
            <p style="font-weight: bold; padding: 5px; border: 1px solid #ccc; background-color: #f5f5f5;">
                <?php echo htmlspecialchars($nombre_anuncio_fijo); ?>
            </p>
            <input type="hidden" name="anuncio_id" value="<?php echo $anuncio_id_fijo; ?>">

        <?php else: ?>
            <select id="anuncio_seleccion" name="anuncio_id" required>
                <option value="" disabled selected>-- Seleccione un anuncio --</option>
                <?php foreach ($anuncios_usuario_lista as $id => $nombre): ?>
                    <option value="<?php echo $id; ?>">
                        <?php echo htmlspecialchars($nombre); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        
        <hr>

    <label for="foto">(*) Seleccionar archivo de foto:</label>
        <input type="file" id="foto" name="foto" accept="image/*" required>

    <label for="titulo_foto">(*) Título de la foto (máx. 50 caracteres):</label>
        <input type="text" id="titulo_foto" name="titulo_foto" required>

    <label for="alt_text">(*) Texto alternativo (mínimo 10 caracteres):</label>
    <input type="text" id="alt_text" name="alt_text" minlength="10" required>
    <p class="info">Se usará para accesibilidad.</p>

        <button type="submit">Subir foto</button>
    </form>

<?php
require_once 'include/footer.php';
?>