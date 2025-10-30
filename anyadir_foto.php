<?php
// Fichero: anadir_foto.php

// Incluir datos y plantillas
require_once 'include/head.php'; 
require_once 'data/anuncios.php'; // Necesitamos los datos para la lista desplegable

$titulo_pagina = "Añadir Foto a Anuncio - PI";

// 1. Lógica Condicional: Capturar ID y determinar el modo del formulario
$anuncio_id_fijo = (int)($_GET['anuncio_id'] ?? 0);
$modo_seleccion = ($anuncio_id_fijo > 0) ? 'fijo' : 'seleccion';

// 2. Preparar datos para la lista desplegable (simulando todos los anuncios del usuario)
$anuncios_usuario_lista = [
    1 => 'Piso céntrico en Madrid (ID: 1)',
    2 => 'Apartamento en Valencia (ID: 2)',
    3 => 'Chalet en Sevilla (ID: 3)',
];

// Si el ID es fijo, buscamos su nombre para mostrarlo
$nombre_anuncio_fijo = $anuncios_usuario_lista[$anuncio_id_fijo] ?? "Anuncio No Encontrado";
?>

    <h2>Añadir Foto a Anuncio</h2>

    <form action="procesar_foto.php" method="post" enctype="multipart/form-data">
        
        <label for="anuncio_seleccion">(*) Anuncio de Destino:</label>
        
        <?php if ($modo_seleccion === 'fijo'): ?>
            <p style="font-weight: bold; padding: 5px; border: 1px solid #ccc; background-color: #f5f5f5;">
                <?php echo htmlspecialchars($nombre_anuncio_fijo); ?>
            </p>
            <input type="hidden" name="anuncio_id" value="<?php echo $anuncio_id_fijo; ?>">

        <?php else: ?>
            <select id="anuncio_seleccion" name="anuncio_id" required>
                <option value="" disabled selected>-- Seleccione un Anuncio --</option>
                <?php foreach ($anuncios_usuario_lista as $id => $nombre): ?>
                    <option value="<?php echo $id; ?>">
                        <?php echo htmlspecialchars($nombre); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        
        <hr>

        <label for="foto">(*) Seleccionar Archivo de Foto:</label>
        <input type="file" id="foto" name="foto" accept="image/*" required>

        <label for="titulo_foto">(*) Título de la Foto (máx. 50 caracteres):</label>
        <input type="text" id="titulo_foto" name="titulo_foto" required>

        <label for="alt_text">(*) Texto Alternativo (Mínimo 10 caracteres):</label>
        <input type="text" id="alt_text" name="alt_text" minlength="10" required>
        <p class="info">Se usará para accesibilidad.</p>

        <button type="submit">Subir Foto</button>
    </form>

<?php
require_once 'include/footer.php';
?>