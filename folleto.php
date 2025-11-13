<?php
$titulo_pagina = "Solicitar folleto - PI";
$body_id = "folletoPage"; 
require_once 'include/head.php'; 
require_once 'include/db_connect.php';
controlar_acceso_privado(); 
$mysqli = conectar_bd();

// Obtener ID usuario
$nom = $_SESSION['usuario'];
$res = $mysqli->query("SELECT IdUsuario FROM USUARIOS WHERE NomUsuario = '$nom'");
$uid = $res->fetch_assoc()['IdUsuario'];

// Obtener sus anuncios
$sql_ads = "SELECT IdAnuncio, Titulo FROM ANUNCIOS WHERE Usuario = $uid";
$res_ads = $mysqli->query($sql_ads);
?>

<form action="respuesta_folleto.php" method="post">
    <label for="anuncio">Anuncio del usuario a imprimir (obligatorio):</label>
    <select id="anuncio" name="anuncio">
        <option value="">--Seleccione un anuncio--</option>
        <?php while($ad = $res_ads->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($ad['Titulo']) ?>">
                <?= htmlspecialchars($ad['Titulo']) ?>
            </option>
        <?php endwhile; ?>
    </select>
    <span id="anuncioError" class="error"></span>

    <button type="submit">Solicitar</button>
</form>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>