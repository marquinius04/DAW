<?php
$titulo_pagina = "Crear anuncio - PI";
require_once 'include/head.php'; 
require_once 'include/db_connect.php';
require_once 'include/select_options.inc.php';
controlar_acceso_privado(); 
$mysqli = conectar_bd();
?>

    <h2>Crear un nuevo anuncio</h2>
    
    <form action="respuesta_crear_anuncio.php" method="post" enctype="multipart/form-data">
        
        <label for="titulo">(*) Título del anuncio:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="tanuncio">Tipo de Anuncio:</label>
        <select id="tanuncio" name="tanuncio" required>
            <?php generar_select_options($mysqli, 'tiposanuncios', 'IdTAnuncio', 'NomTAnuncio'); ?>
        </select>

        <label for="tvivienda">Tipo de Vivienda:</label>
        <select id="tvivienda" name="tvivienda" required>
            <?php generar_select_options($mysqli, 'tiposviviendas', 'IdTVivienda', 'NomTVivienda'); ?>
        </select>

        <label for="pais">País:</label>
        <select id="pais" name="pais" required>
             <?php generar_select_options($mysqli, 'paises', 'IdPais', 'NomPais'); ?>
        </select>

        <label for="ciudad">(*) Ciudad:</label>
        <input type="text" id="ciudad" name="ciudad" required>

        <label for="descripcion">(*) Descripción:</label>
        <textarea id="descripcion" name="descripcion" rows="5" cols="40" required></textarea>

        <label for="precio">Precio (€):</label>
        <input type="number" id="precio" name="precio" min="0" step="0.01">

        <button type="submit">Crear anuncio y añadir fotos</button>
    </form>

<?php
$mysqli->close();
require_once 'include/footer.php'; 
?>