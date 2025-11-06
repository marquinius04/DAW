<?php
$titulo_pagina = "Solicitar folleto publicitario - PI";
$body_id = "folletoPage"; 
// Incluye la cabecera y el gestor de sesión
require_once 'include/head.php'; 
// Controla que solo usuarios logueados puedan acceder a la página
controlar_acceso_privado(); 

?>

    <h2>Solicitar folleto publicitario impreso</h2>
...

    <h2>Solicitar folleto publicitario impreso</h2>
    
    <p>Complete el siguiente formulario para solicitar un folleto impreso basado en sus anuncios. A continuación se muestran las tarifas según las características del folleto:</p>
    
    <table>
      <thead>
        <tr>
          <th>Número de páginas</th>
          <th>Número de fotos</th>
          <th>Impresión</th>
          <th>Resolución (DPI)</th>
          <th>Precio por unidad</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>4</td><td>1-5</td><td>Blanco y negro</td><td>150</td><td>5€</td></tr>
        <tr><td>4</td><td>1-5</td><td>Color</td><td>150</td><td>7€</td></tr>
        <tr><td>8</td><td>6-10</td><td>Blanco y negro</td><td>150</td><td>8€</td></tr>
        <tr><td>8</td><td>6-10</td><td>Color</td><td>150</td><td>12€</td></tr>
      </tbody>
    </table>
    
    <p>Coste de procesamiento y envío: 2€ (fijo, independiente del número de páginas o copias).</p>

    <?php
    // --- Bloque php para generar la tabla de costes ---
    
    // Define constantes de tarifas
    if (!defined('COSTES_FOLLETO')) {
        define('COSTES_FOLLETO', [
            'FIJO' => 10.0,
            'PAG' => [2.0, 1.8, 1.6], 
            'COLOR_FOTO' => 0.5,
            'RES_FOTO' => 0.2
        ]);
    }
    
    // Datos de entrada para la tabla de ejemplo
    $datosEntradaTabla = [
        ['p' => 1, 'f' => 3], ['p' => 2, 'f' => 6], ['p' => 3, 'f' => 9], ['p' => 4, 'f' => 12],
        ['p' => 5, 'f' => 15], ['p' => 6, 'f' => 18], ['p' => 7, 'f' => 21], ['p' => 8, 'f' => 24],
        ['p' => 9, 'f' => 27], ['p' => 10, 'f' => 30], ['p' => 11, 'f' => 33], ['p' => 12, 'f' => 36],
        ['p' => 13, 'f' => 39], ['p' => 14, 'f' => 42], ['p' => 15, 'f' => 45]
    ];
    
    // Función de cálculo del coste del folleto por bloques
    if (!function_exists('calcularCosteFolletoPHP')) {
        function calcularCosteFolletoPHP($numPaginas, $numFotos, $esColor, $esAltaRes) {
            $costePaginas = 0;
            if ($numPaginas <= 4) {
                $costePaginas = $numPaginas * COSTES_FOLLETO['PAG'][0];
            } elseif ($numPaginas <= 10) {
                $costePaginas = (4 * COSTES_FOLLETO['PAG'][0]) + (($numPaginas - 4) * COSTES_FOLLETO['PAG'][1]);
            } else {
                $costePaginas = (4 * COSTES_FOLLETO['PAG'][0]) + (6 * COSTES_FOLLETO['PAG'][1]) + (($numPaginas - 10) * COSTES_FOLLETO['PAG'][2]);
            }
        
            $costeColor = $esColor ? $numFotos * COSTES_FOLLETO['COLOR_FOTO'] : 0;
            $costeResolucion = $esAltaRes ? $numFotos * COSTES_FOLLETO['RES_FOTO'] : 0;
            
            $total = COSTES_FOLLETO['FIJO'] + $costePaginas + $costeColor + $costeResolucion;
            
            return number_format($total, 2, ',', '.') . " €";
        }
    }
    
    // Imprime la tabla html de costes calculados
    echo "<h3>Tabla de costes </h3>";
    echo "<div style='overflow-x: auto;'>";
    echo "<table id='tablaCostesGenerada' style='border-collapse: collapse; margin-top: 15px; width: 100%; max-width: 800px; border: 1px solid #333; text-align: center;'>";
    
    // Cabecera de tabla calculada
    echo "<thead>";
    echo "<tr style='background-color: #E0E0E0;'>";
    echo "<th rowspan='2' style='border: 1px solid #333; background-color: #004aad; padding: 8px;'>Número de páginas</th>";
    echo "<th rowspan='2' style='border: 1px solid #333; background-color: #004aad;padding: 8px;'>Número de fotos</th>";
    echo "<th colspan='2' style='border: 1px solid #333; background-color: #004aad; padding: 8px;'>Blanco y negro</th>";
    echo "<th colspan='2' style='border: 1px solid #333; background-color: #004aad; padding: 8px;'>Color</th>";
    echo "</tr>";
    echo "<tr style='background-color: #E0E0E0;'>";
    echo "<th style='border: 1px solid #333; background-color: #004aad; padding: 8px;'>150-300 dpi</th>";
    echo "<th style='border: 1px solid #333; background-color: #004aad; padding: 8px;'>450-900 dpi</th>";
    echo "<th style='border: 1px solid #333; background-color: #004aad; padding: 8px;'>150-300 dpi</th>";
    echo "<th style='border: 1px solid #333; background-color: #004aad; padding: 8px;'>450-900 dpi</th>";
    echo "</tr>";
    echo "</thead>";
    
    // Cuerpo de la tabla
    echo "<tbody>";
    foreach ($datosEntradaTabla as $fila) {
        $p = $fila['p'];
        $f = $fila['f'];
        echo "<tr>";
        echo "<td style='border: 1px solid #333; padding: 8px;'>{$p}</td>";
        echo "<td style='border: 1px solid #333; padding: 8px;'>{$f}</td>";
        echo "<td style='border: 1px solid #333; padding: 8px;'>" . calcularCosteFolletoPHP($p, $f, false, false) . "</td>";
        echo "<td style='border: 1px solid #333; padding: 8px;'>" . calcularCosteFolletoPHP($p, $f, false, true) . "</td>";
        echo "<td style='border: 1px solid #333; padding: 8px;'>" . calcularCosteFolletoPHP($p, $f, true, false) . "</td>";
        echo "<td style='border: 1px solid #333; padding: 8px;'>" . calcularCosteFolletoPHP($p, $f, true, true) . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table><br>";
    echo "</div>"; 
    
    ?>
    <hr>
    
    <!-- Formulario para solicitar folleto -->
    <form action="respuesta_folleto.php" method="post"> 
      
      <label for="nombre">(*) Nombre y apellidos (máx. 200 caracteres):</label>
      <input type="text" id="nombre" name="nombre">
      <span id="nombreError" class="error"></span>

      <label for="email">(*) Correo electrónico (máx. 200 caracteres):</label>
      <input type="text" id="email" name="email">
      <span id="emailError" class="error"></span>

      <label>(*) Dirección postal:</label>
      <input type="text" id="calle" name="calle" placeholder="Calle">
      <span id="calleError" class="error"></span>
      <input type="text" id="numero" name="numero" placeholder="Número">
      <span id="numeroError" class="error"></span>
      <input type="text" id="piso" name="piso" placeholder="Piso/Letra">
      <input type="text" id="codigo_postal" name="codigo_postal" placeholder="Código Postal">
      <span id="codigo_postalError" class="error"></span>
      <input type="text" id="localidad" name="localidad" placeholder="Localidad">
      <span id="localidadError" class="error"></span>
      <input type="text" id="provincia" name="provincia" placeholder="Provincia">
      <span id="provinciaError" class="error"></span>
      
      <label for="pais">País:</label>
      <select id="pais" name="pais">
        <option value="espana">España</option>
        </select>

      <label for="telefono">Teléfono:</label>
      <input type="text" id="telefono" name="telefono">

      <label for="color_portada">Color de la portada:</label>
      <input type="color" id="color_portada" name="color_portada" value="#000000">

      <label for="num_copias">Número de copias (1-99, por defecto 1):</label>
      <input type="text" id="num_copias" name="num_copias" value="1">
      <span id="numCopiasError" class="error"></span>

      <label for="resolucion">Resolución de las fotos (DPI, 150-900, incrementos de 150, por defecto 150):</label>
      <select id="resolucion" name="resolucion">
        <option value="150" selected>150</option>
        <option value="300">300</option>
        <option value="900">900</option>
      </select>
      
     <label>(*) Tipo de Impresión:</label>
      <input type="radio" id="impresion_bn" name="impresion_color" value="blanco_negro" checked>
      <label for="impresion_bn">Blanco y Negro</label>
      <input type="radio" id="impresion_color_sel" name="impresion_color" value="color">
      <label for="impresion_color_sel">Color</label>
      <span id="impresionColorError" class="error"></span>
      
      <label for="anuncio">Anuncio del usuario a imprimir (obligatorio):</label>
      <select id="anuncio" name="anuncio">
        <option value="">--Seleccione un anuncio--</option>
        <option value="anuncio1">Piso en Madrid, 2 habitaciones</option>
        <option value="anuncio2">Apartamento en Valencia, 1 habitación</option>
      </select>
      <span id="anuncioError" class="error"></span>

      <label for="fecha_recepcion">Fecha aproximada de recepción:</label>
      <div class="containerNacimiento">
        <input type="text" id="diaRecepcion" name="diaRecepcion" class="fechaNacimiento" placeholder="DD">
        /
        <input type="text" id="mesRecepcion" name="mesRecepcion" class="fechaNacimiento" placeholder="MM">
        /
        <input type="text" id="anyoRecepcion" name="anyoRecepcion" class="fechaNacimiento" placeholder="AAAA">
      </div>

      <label>Impresión del precio:</label>
      <input type="radio" id="con_precio" name="impresion_precio" value="con_precio" checked>
      <label for="con_precio">Con precio</label>
      <input type="radio" id="sin_precio" name="impresion_precio" value="sin_precio">
      <label for="sin_precio">Sin precio</label>

      <label for="texto_adicional">Texto adicional (opcional, máximo 4000 caracteres):</label>
      <textarea id="texto_adicional" name="texto_adicional" rows="6" cols="50"></textarea>

      <button type="submit">Solicitar folleto</button>
    </form>
  
<?php
// require_once 'js/validaciones.js';
require_once 'include/footer.php';
?>