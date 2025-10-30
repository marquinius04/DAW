<?php
// Fichero: folleto.php

// Define las variables para la plantilla
$titulo_pagina = "Solicitar folleto publicitario - PI";
$body_id = "folletoPage"; 
// NOTA: Asegúrate que esta ruta es correcta ('include' o 'includes')
require_once 'include/head.php'; 
?>

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

    <button type="button" id="toggleTablaCostes">Mostrar tabla de costes</button>
    <div id="contenedorTablaCostes" style="overflow-x: auto;"></div>
    <hr>
    
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
      
      <br><br>
      <label>(*) Tipo de Impresión:</label>
      <input type="radio" id="impresion_bn" name="impresion_color" value="blanco_negro" checked>
      <label for="impresion_bn">Blanco y Negro</label>
      <input type="radio" id="impresion_color_sel" name="impresion_color" value="color">
      <label for="impresion_color_sel">Color</label>
      <span id="impresionColorError" class="error"></span>
      <br><br>
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
// COMENTAMOS O ELIMINAMOS EL SCRIPT JS DE VALIDACIÓN
// require_once 'js/validaciones.js'; 
require_once 'include/footer.php';
?>