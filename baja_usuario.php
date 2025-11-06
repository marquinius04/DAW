<?php
$titulo_pagina = "Darse de baja - PI";

// Incluye la cabecera y el gestor de sesión
require_once 'include/head.php'; 
// Controla que solo usuarios logueados puedan acceder a la página
controlar_acceso_privado(); 
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