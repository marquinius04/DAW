<?php
$titulo_pagina = "Enviar Mensaje - PI";
$anuncio_id = htmlspecialchars($_GET['anuncio_id'] ?? 'N/A');
require_once 'include/head.php'; 
require_once 'include/db_connect.php';
require_once 'include/select_options.inc.php'; 
controlar_acceso_privado(); 
$mysqli = conectar_bd();
?>

    <h2>Enviar mensaje al anunciante</h2>
    
    <form action="mensaje_respuesta.php" method="post">
      <input type="hidden" name="anuncio_id" value="<?php echo $anuncio_id; ?>">
      
      <label for="tipo">Tipo de mensaje:</label>
      <select id="tipo" name="tipo_mensaje" required>
        <option value="" disabled selected>Selecciona el tipo de mensaje</option>
        <?php
            generar_select_options($mysqli, 'TIPOSMENSAJES', 'NomTMensaje', 'NomTMensaje');
        ?>
      </select>

      <label for="email_remitente">Tu correo electrónico:</label>
      <input type="email" id="email_remitente" name="email_remitente" required>
      
      <label for="texto">Mensaje:</label>
      <textarea id="texto" name="mensaje_texto" rows="5" cols="40" required></textarea> 
      
      <button type="submit">Enviar</button>
    </form>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>