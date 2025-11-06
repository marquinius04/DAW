<?php
$titulo_pagina = "Enviar Mensaje - PI";
// Obtiene el id del anuncio de la url para mantener el contexto
$anuncio_id = htmlspecialchars($_GET['anuncio_id'] ?? 'N/A');
// Incluye la cabecera y el gestor de sesión
require_once 'include/head.php'; 
// Controla que solo usuarios logueados puedan acceder a la página
controlar_acceso_privado(); 
?>

    <h2>Enviar mensaje al anunciante</h2>
    
    <form action="respuesta_mensaje.php" method="post">
      
      <input type="hidden" name="anuncio_id" value="<?php echo $anuncio_id; ?>">
      
      <label for="tipo">Tipo de mensaje:</label>
      <select id="tipo" name="tipo_mensaje"> <option value="" disabled selected>Selecciona el tipo de mensaje</option>
        <option value="info">Más información</option>
        <option value="cita">Solicitar una cita</option>
        <option value="oferta">Comunicar una oferta</option>
      </select>

      <label for="email_remitente">Tu correo electrónico:</label>
      <input type="email" id="email_remitente" name="email_remitente" value="">
      
      <label for="texto">Mensaje:</label>
      <textarea id="texto" name="mensaje_texto" rows="5" cols="40"></textarea> <button type="submit">Enviar</button>
    </form>

<?php
require_once 'include/footer.php';
?>