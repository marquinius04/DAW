<?php
$titulo_pagina = "Crear anuncio - PI";
require_once 'include/head.php'; 
require_once 'include/db_connect.php';
require_once 'include/select_options.inc.php';
controlar_acceso_privado(); 
$mysqli = conectar_bd();
$anuncio = []; // Inicializar $anuncio vacío para el nuevo formulario
?>

    <h2>Crear un nuevo anuncio</h2>
    
    <form action="respuesta_crear_anuncio.php" method="post" enctype="multipart/form-data">
        
        <?php 
        // Aislamiento del formulario
        require 'include/anuncio_form.inc.php'; 
        ?>

        <button type="submit">Crear anuncio y añadir fotos</button>
    </form>

<?php
$mysqli->close();
require_once 'include/footer.php'; 
?>