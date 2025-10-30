<?php
$titulo_pagina = "Búsqueda avanzada - PI";
require_once 'include/head.php'; 
?>

    <h2>Búsqueda avanzada</h2>
    <form action="resultados.php" method="get"> <label for="tipo_anuncio">Tipo de anuncio:</label>
      <select id="tipo_anuncio" name="tipo_anuncio">
        <option value="Venta">Venta</option>
        <option value="Alquiler">Alquiler</option>
      </select>
      
      <label for="ciudad">Ciudad:</label>
      <input type="text" id="ciudad" name="ciudad">

      <label for="pais">País:</label>
      <select id="pais" name="pais">
        <option value="España">España</option>
        </select>

      <label for="precio">Precio máximo (€):</label>
      <input type="number" id="precio" name="precio" step="1000">
      
      <button type="submit">Buscar</button>
    </form>

<?php
require_once 'include/footer.php';
?>