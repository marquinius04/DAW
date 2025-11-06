<?php
$titulo_pagina = "Crear anuncio - PI";
require_once 'include/head.php'; 
controlar_acceso_privado(); 
?>

    <h2>Crear un nuevo anuncio</h2>
    
    <form action="index_logueado.php" method="post" enctype="multipart/form-data">
        
        <label for="titulo">Título del anuncio:</label>
        <input type="text" id="titulo" name="titulo">

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" rows="5" cols="40"></textarea>

        <label for="precio">Precio (€):</label>
        <input type="number" id="precio" name="precio" min="0" step="1000">

        <label for="ubicacion">Ubicación (Ciudad/País):</label>
        <input type="text" id="ubicacion" name="ubicacion">

        <label for="fecha">Fecha de publicación:</label>
        <input type="date" id="fecha" name="fecha">

        <label for="foto_principal">Foto Principal:</label>
        <input type="file" id="foto_principal" name="foto_principal" accept="image/*">

        <input type="submit" value="Publicar anuncio">
    </form>

<?php
require_once 'include/footer.php'; 
?>