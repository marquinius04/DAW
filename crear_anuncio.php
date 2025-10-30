<?php
// Fichero: crear_anuncio.php

// Define variables para la plantilla
$titulo_pagina = "Crear anuncio - PI";
require_once 'include/head.php'; // Incluye la cabecera, navegación y apertura de <main>
?>

    <h2>Crear un nuevo anuncio</h2>
    
    <form action="procesar_anuncio.php" method="post" enctype="multipart/form-data">
        
        <label for="titulo">Título del anuncio:</label><br>
        <input type="text" id="titulo" name="titulo"><br><br>

        <label for="descripcion">Descripción:</label><br>
        <textarea id="descripcion" name="descripcion" rows="5" cols="40"></textarea><br><br>

        <label for="precio">Precio (€):</label><br>
        <input type="number" id="precio" name="precio" min="0" step="1000"><br><br>

        <label for="ubicacion">Ubicación (Ciudad/País):</label><br>
        <input type="text" id="ubicacion" name="ubicacion"><br><br>

        <label for="fecha">Fecha de publicación:</label><br>
        <input type="date" id="fecha" name="fecha"><br><br>

        <label for="foto_principal">Foto Principal:</label><br>
        <input type="file" id="foto_principal" name="foto_principal" accept="image/*"><br><br>

        <input type="submit" value="Publicar anuncio">
    </form>

<?php
require_once 'include/footer.php'; // Cierra el <main> y el <body>
?>