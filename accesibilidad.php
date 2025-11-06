<?php
$titulo_pagina = "Declaración de Accesibilidad - PI";
require_once 'include/head.php'; 
?>

    <section>
      <h2>Declaración de Accesibilidad</h2>
      <p>El sitio web <strong>PI - Pisos & Inmuebles</strong> ha sido desarrollado siguiendo principios de accesibilidad web
      para facilitar su uso a todas las personas, incluyendo aquellas con discapacidades visuales o de movilidad.</p>
    </section>

    <section>
      <h3>Medidas adoptadas</h3>
      <ul>
        <li>Uso de etiquetas semánticas correctas: <code>&lt;header&gt;</code>, <code>&lt;main&gt;</code>, <code>&lt;footer&gt;</code>, <code>&lt;section&gt;</code>.</li>
        <li>Todas las imágenes incluyen texto alternativo mediante el atributo <code>alt</code>.</li>
        <li>Los formularios incluyen etiquetas <code>&lt;label&gt;</code> asociadas a cada campo.</li>
        <li>Se han definido distintos estilos accesibles para mejorar la legibilidad y el contraste.</li>
      </ul>
    </section>

    <section>
      <h3>Estilos accesibles disponibles</h3>
      <p>El usuario puede cambiar el estilo de visualización desde el navegador utilizando la opción
      <em>Ver → Estilo de página</em> (en Firefox). Los estilos disponibles son:</p>
      <ul>
        <li><strong>Modo noche:</strong> fondo oscuro y texto claro.</li>
        <li><strong>Alto contraste:</strong> texto blanco sobre fondo negro y enlaces destacados.</li>
        <li><strong>Texto grande:</strong> aumenta el tamaño de toda la tipografía.</li>
        <li><strong>Contraste + texto grande:</strong> combina ambos ajustes anteriores.</li>
        <li><strong>Versión de impresión:</strong> fondo blanco y diseño simplificado para impresión.</li>
      </ul>
    </section>

    <section>
      <h3>Compatibilidad</h3>
      <p>Los estilos alternativos son compatibles con los principales navegadores modernos, aunque la selección manual de estilos
      está disponible principalmente en <strong>Mozilla Firefox</strong>. En otros navegadores, puede activarse mediante extensiones
      o herramientas de accesibilidad del sistema operativo.</p>
    </section>

    <section>
      <h3>Contacto</h3>
      <p>Si detecta algún problema de accesibilidad o desea realizar sugerencias, puede ponerse en contacto con los autores del sitio:</p>
      <p><strong>Marcos Díaz Moleón</strong> y <strong>Gustavo Joel Paladines Dávila</strong>.</p>
    </section>
    
    <?php
require_once 'include/footer.php'; // Se encarga del footer y el enlace actualizado
?>