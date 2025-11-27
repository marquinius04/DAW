<?php

// Asignar valores por defecto para nuevo anuncio
$val_titulo = $anuncio['Titulo'] ?? '';
$val_descripcion = $anuncio['Texto'] ?? '';
$val_ciudad = $anuncio['Ciudad'] ?? '';
$val_precio = $anuncio['Precio'] ?? '';
$val_tanuncio = $anuncio['TAnuncio'] ?? null;
$val_tvivienda = $anuncio['TVivienda'] ?? null;
$val_pais = $anuncio['Pais'] ?? null;
?>

<label for="titulo">(*) Título del anuncio:</label>
<input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($val_titulo) ?>" required>

<label for="tanuncio">Tipo de Anuncio:</label>
<select id="tanuncio" name="tanuncio" required>
    <?php generar_select_options($mysqli, 'tiposanuncios', 'IdTAnuncio', 'NomTAnuncio', $val_tanuncio); ?>
</select>

<label for="tvivienda">Tipo de Vivienda:</label>
<select id="tvivienda" name="tvivienda" required>
    <?php generar_select_options($mysqli, 'tiposviviendas', 'IdTVivienda', 'NomTVivienda', $val_tvivienda); ?>
</select>

<label for="pais">País:</label>
<select id="pais" name="pais" required>
     <?php generar_select_options($mysqli, 'paises', 'IdPais', 'NomPais', $val_pais); ?>
</select>

<label for="ciudad">(*) Ciudad:</label>
<input type="text" id="ciudad" name="ciudad" value="<?= htmlspecialchars($val_ciudad) ?>" required>

<label for="descripcion">(*) Descripción:</label>
<textarea id="descripcion" name="descripcion" rows="5" cols="40" required><?= htmlspecialchars($val_descripcion) ?></textarea>

<label for="precio">Precio (€):</label>
<input type="number" id="precio" name="precio" min="0" step="0.01" value="<?= $val_precio ?>">