<?php
$titulo_pagina = "Modificar datos - PI";
// Incluye la cabecera y el gestor de sesión
require_once 'include/head.php'; 
// Controla que solo usuarios logueados puedan acceder a la página
controlar_acceso_privado(); 
?>

    <h2>Modificar mis datos</h2>
    <form action="perfil.php" method="post"> 
      <label for="nombre">Nombre:</label>
      <input type="text" id="nombre" name="nombre" value="Marcos">

      <label for="apellidos">Apellidos:</label>
      <input type="text" id="apellidos" name="apellidos" value="Díaz Moleón">

      <label for="email">Correo electrónico:</label>
      <input type="email" id="email" name="email" value="ejemplo@correo.com">

      <label for="telefono">Teléfono:</label>
      <input type="text" id="telefono" name="telefono" value="600123456">

      <label for="direccion">Dirección:</label>
      <input type="text" id="direccion" name="direccion" value="Calle Falsa 123, Madrid">

      <button type="submit">Guardar cambios</button>
    </form>

<?php
require_once 'include/footer.php';
?>