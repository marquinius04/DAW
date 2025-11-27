<?php
$titulo_pagina = "Modificar Anuncio - PI";
require_once 'include/head.php'; 
require_once 'include/db_connect.php';
require_once 'include/select_options.inc.php';
controlar_acceso_privado();

$id_anuncio = (int)($_GET['id'] ?? 0);
$mysqli = conectar_bd();

// Obtener datos y verificar propiedad
$stmt = $mysqli->prepare("SELECT * FROM anuncios WHERE IdAnuncio = ? AND Usuario = ?");
$user_id = $_SESSION['id_usuario'];
$stmt->bind_param("ii", $id_anuncio, $user_id);
$stmt->execute();
$res = $stmt->get_result();
$anuncio = $res->fetch_assoc();

if (!$anuncio) {
    echo "<h2>Anuncio no encontrado o no tienes permiso.</h2>";
    require_once 'include/footer.php';
    exit();
}
?>

<h2>Modificar Anuncio</h2>

<form action="respuesta_modificar_anuncio.php" method="post">
    <input type="hidden" name="id_anuncio" value="<?= $id_anuncio ?>">
    
    <label for="titulo">(*) Título:</label>
    <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($anuncio['Titulo']) ?>" required>

    <label for="tanuncio">Tipo de Anuncio:</label>
    <select id="tanuncio" name="tanuncio" required>
        <?php generar_select_options($mysqli, 'tiposanuncios', 'IdTAnuncio', 'NomTAnuncio', $anuncio['TAnuncio']); ?>
    </select>

    <label for="tvivienda">Tipo de Vivienda:</label>
    <select id="tvivienda" name="tvivienda" required>
        <?php generar_select_options($mysqli, 'tiposviviendas', 'IdTVivienda', 'NomTVivienda', $anuncio['TVivienda']); ?>
    </select>

    <label for="pais">País:</label>
    <select id="pais" name="pais" required>
            <?php generar_select_options($mysqli, 'paises', 'IdPais', 'NomPais', $anuncio['Pais']); ?>
    </select>

    <label for="ciudad">(*) Ciudad:</label>
    <input type="text" id="ciudad" name="ciudad" value="<?= htmlspecialchars($anuncio['Ciudad']) ?>" required>

    <label for="descripcion">(*) Descripción:</label>
    <textarea id="descripcion" name="descripcion" rows="5" required><?= htmlspecialchars($anuncio['Texto']) ?></textarea>

    <label for="precio">Precio (€):</label>
    <input type="number" id="precio" name="precio" step="0.01" value="<?= $anuncio['Precio'] ?>">

    <button type="submit">Guardar cambios</button>
    <a href="mis_anuncios.php" style="margin-left: 10px;">Cancelar</a>
</form>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>