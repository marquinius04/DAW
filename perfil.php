<?php
$titulo_pagina = "Perfil de usuario registrado - PI";
require_once 'include/head.php'; 
// Controla que solo usuarios logueados puedan acceder a la página
controlar_acceso_privado();
?>

    <h2>Perfil de usuario registrado</h2>
    <p>Seleccione la acción que desea realizar:</p>
    <ul>
      <li><a href="configurar.php"><span class="icono">settings</span>Configurar estilo</a></li>
      <li><a href="mis_mensajes.php"><span class="icono">mail</span>Mis mensajes</a></li>
      <li><a href="mis_anuncios.php"><span class="icono">article</span>Visualizar mis anuncios</a></li>
      <li><a href="configurar.php"><span class="icono">settings</span>Configurar estilo</a></li>
      <li><a href="crear_anuncio.php"><span class="icono">add_circle</span>Crear un anuncio nuevo</a></li>
      <li><a href="modificar_datos.php"><span class="icono">manage_accounts</span>Modificar mis datos</a></li>
      <li><a href="folleto.php"><span class="icono">description</span>Solicitar folleto publicitario impreso</a></li>
      <li><a href="index.php"><span class="icono">logout</span>Salir</a></li>
      <li><a href="baja_usuario.php"><span class="icono">delete</span>Darme de baja</a></li>
    </ul>

<?php
require_once 'include/footer.php';
?>