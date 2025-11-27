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
        
        <?php 
        // Aislamiento del formulario
        require 'include/anuncio_form.inc.php'; 
        ?>

        <button type="submit">Guardar cambios</button>
        <a href="mis_anuncios.php" style="margin-left: 10px;">Cancelar</a>
    </form>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>