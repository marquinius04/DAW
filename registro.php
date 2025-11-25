<?php
$titulo_pagina = "Registro de Nuevo Usuario - PI";
$body_id = "registroPage"; 
$menu_tipo = 'publico'; 
require_once 'include/flashdata.inc.php'; 
require_once 'include/db_connect.php';
require_once 'include/select_options.inc.php'; 

$flash_error = get_flashdata('error');

require_once 'include/head.php';
controlar_acceso_publico();

$mysqli = conectar_bd();

// Datos para repoblar el formulario 
$val_usuario = htmlspecialchars($_GET['usuario'] ?? '');
$val_email = htmlspecialchars($_GET['email'] ?? '');
$val_sexo = htmlspecialchars($_GET['sexo'] ?? '');
$val_dia = htmlspecialchars($_GET['diaNacimiento'] ?? '');
$val_mes = htmlspecialchars($_GET['mesNacimiento'] ?? '');
$val_anyo = htmlspecialchars($_GET['anyoNacimiento'] ?? '');
// Convertimos el valor de país a entero, ya que el ID del país es un número
$val_ciudad = htmlspecialchars($_GET['ciudad'] ?? '');
$val_pais = (int)($_GET['pais'] ?? 0);

?>
    <h2>Registro de nuevo usuario</h2>
    <form action="respuesta_registro.php" method="post" enctype="multipart/form-data">
        
      <label for="usuario">(*) Nombre de usuario:</label>
      <input type="text" id="usuario" name="usuario" value="<?php echo $val_usuario; ?>">
      <ul id="usuarioInfo" class="info">
        <li>· Sólo puede contener letras del alfabeto inglés y números</li>
        <li>· No puede comenzar por un número</li>
        <li>· Longitud 3-15 caracteres</li>
      </ul>
      <span id="usuarioError" class="error"></span> <label for="clave">(*) Contraseña:</label>
      <input type="password" id="clave" name="clave">
      <ul id="claveInfo" class="info">
        <li>· Sólo puede contener letras del alfabeto inglés, números, "-" y "_"</li>
        <li>· Al menos una mayúscula, una minúscula y un número</li>
        <li>· Longitud 6-15</li>
      </ul>
      <span id="claveError" class="error"></span> 

      <label for="clave2">(*) Repetir contraseña:</label>
      <input type="password" id="clave2" name="clave2">
      <span id="clave2Error" class="error"></span> 

      <label for="email">(*) Correo electrónico:</label>
      <input type="text" id="email" name="email" value="<?php echo $val_email; ?>">
      <span id="emailError" class="error"></span> 

      <label>(*) Sexo:</label>
      <select name="sexo" id="sexo">
        <option value="">-- Seleccione una opción --</option>
        <option value="Hombre" <?php echo ($val_sexo == 'Hombre') ? 'selected' : ''; ?>>Hombre</option>
        <option value="Mujer" <?php echo ($val_sexo == 'Mujer') ? 'selected' : ''; ?>>Mujer</option>
        <option value="Otro" <?php echo ($val_sexo == 'Otro') ? 'selected' : ''; ?>>Prefiero no contestar</option>
      </select>
      <span id="sexoError" class="error"></span> 

      <label for="nacimiento">(*) Fecha de nacimiento:</label>
      <div class="containerNacimiento">
        <input type="text" id="diaNacimiento" name="diaNacimiento" class="fechaNacimiento" placeholder="DD" value="<?php echo $val_dia; ?>">
        /
        <input type="text" id="mesNacimiento" name="mesNacimiento" class="fechaNacimiento" placeholder="MM" value="<?php echo $val_mes; ?>">
        /
        <input type="text" id="anyoNacimiento" name="anyoNacimiento" class="fechaNacimiento" placeholder="AAAA" value="<?php echo $val_anyo; ?>">
      </div>
      <span id="nacimientoError" class="error"></span> 

      <label for="ciudad">Ciudad de residencia:</label>
      <input type="text" id="ciudad" name="ciudad" value="<?php echo $val_ciudad; ?>">
      <span id="ciudadError" class="error"></span> 

      <label for="pais">País de residencia:</label>
      <select id="pais" name="pais">
        <option value="">-- Seleccione una opción --</option>
        <?php
        // Llama a la función para generar las opciones de la tabla paises
        // Pasa $val_pais como el ID seleccionado 
        generar_select_options($mysqli, 'paises', 'IdPais', 'NomPais', $val_pais);
        ?>
        </select>
      <span id="paisError" class="error"></span> 

      <label for="foto">Foto de perfil:</label>
      <input type="file" id="foto" name="foto" accept="image/*">
      <span id="fotoError" class="error"></span> 

      <button type="submit">Registrarse</button>
    </form>
  
<?php
$mysqli->close();
require_once 'include/footer.php';
?>