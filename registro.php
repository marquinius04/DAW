<?php
// Fichero: registro.php

// Define variables para la plantilla
$titulo_pagina = "Registro de Nuevo Usuario - PI";
$body_id = "registroPage";
$menu_tipo = 'publico'; 
require_once 'include/head.php'; 
?>

    <?php
    if (isset($_GET['error'])) {
        $error = htmlspecialchars($_GET['error']);
        echo "<p style='color: red; border: 1px solid red; padding: 10px; background-color: #ffeaea; margin-top: 15px;'>
                ⛔ Error de Registro: {$error}
              </p>";
    }
    ?>
    <h2>Registro de nuevo usuario</h2>
    <form action="respuesta_registro.php" method="post" enctype="multipart/form-data">
        
      <label for="usuario">(*) Nombre de usuario:</label>
      <input type="text" id="usuario" name="usuario" >
      <ul id="usuarioInfo" class="info">
        <li>· Sólo puede contener letras del alfabeto inglés y números</li>
        <li>· No puede comenzar por un número</li>
        <li>· Longitud 3-15 caracteres</li>
      </ul>
      <span id="usuarioError" class="error"></span> 

      <label for="clave">(*) Contraseña:</label>
      <input type="password" id="clave" name="clave" > <ul id="claveInfo" class="info">
        <li>· Sólo puede contener letras del alfabeto inglés, números, "-" y "_"</li>
        <li>· Al menos una mayúscula, una minúscula y un número</li>
        <li>· Longitud 6-15</li>
      </ul>
      <span id="claveError" class="error"></span> 

      <label for="clave2">(*) Repetir contraseña:</label>
      <input type="password" id="clave2" name="clave2" > <span id="clave2Error" class="error"></span> 

      <label for="email">(*) Correo electrónico:</label>
      <input type="text" id="email" name="email" >
      <span id="emailError" class="error"></span> 

      <label>(*) Sexo:</label>
      <select name="sexo" id="sexo">
        <option value="">-- Seleccione una opción --</option>
        <option value="Hombre">Hombre</option>
        <option value="Mujer">Mujer</option>
        <option value="Otro">Prefiero no contestar</option>
      </select>
      <span id="sexoError" class="error"></span> 

      <label for="nacimiento">(*) Fecha de nacimiento:</label>
      <div class="containerNacimiento">
        <input type="text" id="diaNacimiento" name="diaNacimiento" class="fechaNacimiento" placeholder="DD">
        /
        <input type="text" id="mesNacimiento" name="mesNacimiento" class="fechaNacimiento" placeholder="MM">
        /
        <input type="text" id="anyoNacimiento" name="anyoNacimiento" class="fechaNacimiento" placeholder="AAAA">
      </div>
      <span id="nacimientoError" class="error"></span> 

      <label for="ciudad">Ciudad de residencia:</label>
      <input type="text" id="ciudad" name="ciudad">
      <span id="ciudadError" class="error"></span> 

      <label for="pais">País de residencia:</label>
      <select id="pais" name="pais">
        <option value="">-- Seleccione una opción --</option>
        </select>
      <span id="paisError" class="error"></span> 

      <label for="foto">Foto de perfil:</label>
      <input type="file" id="foto" name="foto" accept="image/*">
      <span id="fotoError" class="error"></span> 

      <button type="submit">Registrarse</button>
    </form>
  
<?php
// Eliminamos la llamada al script de JS de validación:
// <script src="js/validaciones.js"></script>

require_once 'include/footer.php';
?>