<?php
$titulo_pagina = "Búsqueda avanzada - PI";

// 1. INCLUSIONES DE BD Y FUNCIONES
// Incluye la cabecera (que ya incluye sesion.php)
require_once 'include/head.php'; 
// Incluimos los módulos necesarios para la BD
require_once 'include/db_connect.php';
require_once 'include/select_options.inc.php'; 

// 2. CONEXIÓN A BD
$mysqli = conectar_bd();

// (Variables para repoblar el formulario en caso de error)
$val_t_anuncio = (int)($_GET['tipo_anuncio'] ?? 0);
$val_t_vivienda = (int)($_GET['tipo_vivienda'] ?? 0);
$val_pais = (int)($_GET['pais'] ?? 0);
$val_ciudad = htmlspecialchars($_GET['ciudad'] ?? '');
$val_precio = htmlspecialchars($_GET['precio'] ?? '');
?>

    <h2>Búsqueda avanzada</h2>
    <form action="resultados.php" method="post"> 
      
      <label for="tipo_anuncio">Tipo de anuncio:</label>
      <select id="tipo_anuncio" name="tipo_anuncio">
        <option value="">Cualquiera</option>
        <?php 
        // Rellena dinámicamente desde la tabla TIPOSANUNCIOS
        generar_select_options($mysqli, 'TIPOSANUNCIOS', 'IdTAnuncio', 'NomTAnuncio', $val_t_anuncio); 
        ?>
      </select>
      
      <label for="tipo_vivienda">Tipo de vivienda:</label>
      <select id="tipo_vivienda" name="tipo_vivienda">
        <option value="">Cualquiera</option>
        <?php 
        // Rellena dinámicamente desde la tabla TIPOSVIVIENDAS
        generar_select_options($mysqli, 'TIPOSVIVIENDAS', 'IdTVivienda', 'NomTVivienda', $val_t_vivienda); 
        ?>
      </select>

      <label for="ciudad">Ciudad:</label>
      <input type="text" id="ciudad" name="ciudad" value="<?php echo $val_ciudad; ?>">

      <label for="pais">País:</label>
      <select id="pais" name="pais">
        <option value="">Cualquiera</option>
        <?php 
        // Rellena dinámicamente desde la tabla PAISES
        generar_select_options($mysqli, 'PAISES', 'IdPais', 'NomPais', $val_pais); 
        ?>
      </select>

      <label for="precio">Precio máximo (€):</label>
      <input type="number" id="precio" name="precio" step="1000" value="<?php echo $val_precio; ?>">
      
      <button type="submit" name="search_submit">Buscar</button>
    </form>

<?php
// 3. CIERRE DE CONEXIÓN Y FOOTER
$mysqli->close();
require_once 'include/footer.php';
?>