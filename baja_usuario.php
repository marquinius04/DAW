<?php
// Fichero: baja_usuario.php
$titulo_pagina = "Darse de baja - PI";
require_once 'include/head.php'; 
?>

    <h2>Darse de baja</h2>
    <p>Si confirma esta acción, su cuenta de usuario será eliminada permanentemente junto con todos sus anuncios y mensajes.</p>
    
    <form action="index.php" method="post"> <p>¿Está seguro de que desea darse de baja?</p>
      <button type="submit">Sí, eliminar mi cuenta</button>
      <button type="button" onclick="history.back()">No, volver atrás</button>
    </form>

<?php
require_once 'include/footer.php';
?>