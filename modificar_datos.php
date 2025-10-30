<?php
// Fichero: modificar_datos.php
$titulo_pagina = "Modificar datos - PI";
require_once 'include/head.php'; 
?>

    <h2>Modificar mis datos</h2>
    <form action="perfil.php" method="post"> 
      <label for="nombre">Nombre:</label><br>
      <input type="text" id="nombre" name="nombre" value="Marcos"><br><br>

      <label for="apellidos">Apellidos:</label><br>
      <input type="text" id="apellidos" name="apellidos" value="Díaz Moleón"><br><br>

      <label for="email">Correo electrónico:</label><br>
      <input type="email" id="email" name="email" value="ejemplo@correo.com"><br><br>

      <label for="telefono">Teléfono:</label><br>
      <input type="text" id="telefono" name="telefono" value="600123456"><br><br>

      <label for="direccion">Dirección:</label><br>
      <input type="text" id="direccion" name="direccion" value="Calle Falsa 123, Madrid"><br><br>

      <button type="submit">Guardar cambios</button>
    </form>

<?php
require_once 'include/footer.php';
?>