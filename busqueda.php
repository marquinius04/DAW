<?php
// Fichero: busqueda.php
$titulo_pagina = "Búsqueda Avanzada - PI";
// Si el menú no es dinámico, asumimos el menú público por defecto del head.php
require_once 'include/head.php'; 
?>

    <h2>Búsqueda Avanzada</h2>
    <form action="resultados.php" method="get"> <label for="tipo_anuncio">Tipo de anuncio:</label>
      <select id="tipo_anuncio" name="tipo_anuncio">
        <option value="venta">Venta</option>
        <option value="alquiler">Alquiler</option>
      </select><br><br>
      
      <label for="ciudad">Ciudad:</label>
      <input type="text" id="ciudad" name="ciudad"><br><br>

      <label for="pais">País:</label>
      <select id="pais" name="pais">
        <option value="espana">España</option>
        </select><br><br>

      <label for="precio">Precio máximo (€):</label>
      <input type="number" id="precio" name="precio" step="1000"><br><br>
      
      <button type="submit">Buscar</button>
    </form>

<?php
require_once 'include/footer.php';
?>