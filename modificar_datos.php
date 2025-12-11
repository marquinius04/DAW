<?php
$titulo_pagina = "Mis datos - PI";
$body_id = "modificarDatosPage"; 
$menu_tipo = 'privado'; 

require_once 'include/head.php'; 
require_once 'include/db_connect.php';
require_once 'include/select_options.inc.php';
require_once 'include/flashdata.inc.php'; 

controlar_acceso_privado();

$mysqli = conectar_bd();
$id_usuario = $_SESSION['id_usuario'];

$sql_usuario = "
    SELECT U.NomUsuario, U.Clave, U.Email, U.Sexo, U.FNacimiento, U.Ciudad, U.Pais, U.Estilo, U.Foto, E.Nombre
    FROM usuarios U
    JOIN estilos E ON U.Estilo = E.IdEstilo
    WHERE U.IdUsuario = ?
";

$stmt = $mysqli->prepare($sql_usuario);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$datos_usuario = $resultado->fetch_assoc();

if (!$datos_usuario) { header("Location: logout.php"); exit(); }
$stmt->close();

// Variables para el formulario
$val_usuario = htmlspecialchars($datos_usuario['NomUsuario']);
$val_email = htmlspecialchars($datos_usuario['Email']);
$val_sexo = $datos_usuario['Sexo']; 
$val_ciudad = htmlspecialchars($datos_usuario['Ciudad']);
$val_pais = $datos_usuario['Pais']; 
$val_estilo = $datos_usuario['Estilo'];
$val_foto = htmlspecialchars($datos_usuario['Foto'] ?? 'img/default_user.jpg');

$fecha_nacimiento = new DateTime($datos_usuario['FNacimiento']);
$val_dia = $fecha_nacimiento->format('d');
$val_mes = $fecha_nacimiento->format('m');
$val_anyo = $fecha_nacimiento->format('Y');

$flash_success = get_flashdata('success');
$flash_error = get_flashdata('error');
?>

    <h2>Modificar mis datos</h2>

    <?php if ($flash_success): ?>
        <p style="color: green; border: 1px solid green; padding: 10px; background-color: #ffeaea;">✅ <?php echo $flash_success; ?></p>
    <?php endif; ?>
    <?php if ($flash_error): ?>
        <p style="color: red; border: 1px solid red; padding: 10px; background-color: #ffeaea;">⚠️ <?php echo $flash_error; ?></p>
    <?php endif; ?>

    <form action="respuesta_mis_datos.php" method="post" enctype="multipart/form-data">
        
      <fieldset style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;">
        <legend>Foto de perfil</legend>
        <div style="display: flex; align-items: center; gap: 20px;">
            <img src="<?php echo $val_foto; ?>" alt="Foto actual" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
            
            <div style="flex: 1;">
                <label for="foto">Cambiar foto:</label>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>
            
            <?php 
            // Si la foto NO es la por defecto, mostramos botón para eliminarla
            if (basename($val_foto) !== 'default_user.jpg'): 
            ?>
                <div style="text-align: right;">
                    <a href="eliminar_foto_perfil.php" onclick="return confirm('¿Borrar foto de perfil?');" style="color: red; font-size: 0.9em;">
                        <span class="icono">delete</span> Eliminar foto
                    </a>
                </div>
            <?php endif; ?>
        </div>
      </fieldset>

      <label for="usuario">Nombre de usuario:</label>
      <input type="text" id="usuario" name="usuario" value="<?php echo $val_usuario; ?>" readonly style="background-color: #eee;">
      
      <label for="clave_actual">(*) Contraseña actual (necesaria para guardar):</label>
      <input type="password" id="clave_actual" name="clave_actual" required>

      <hr style="margin: 20px 0;">
      
      <label for="nueva_clave">Nueva contraseña (opcional):</label>
      <input type="password" id="nueva_clave" name="nueva_clave">

      <label for="nueva_clave2">Repetir nueva contraseña:</label>
      <input type="password" id="nueva_clave2" name="nueva_clave2">
      
      <label for="email">Email:</label>
      <input type="text" id="email" name="email" value="<?php echo $val_email; ?>">

      <label>Sexo:</label>
      <select name="sexo" id="sexo">
        <option value="1" <?php echo ($val_sexo == 1) ? 'selected' : ''; ?>>Hombre</option>
        <option value="0" <?php echo ($val_sexo == 0) ? 'selected' : ''; ?>>Mujer</option>
        <option value="2" <?php echo ($val_sexo == 2) ? 'selected' : ''; ?>>Otro</option>
      </select>

      <label>Fecha Nacimiento:</label>
      <div class="containerNacimiento">
        <input type="text" name="diaNacimiento" placeholder="DD" value="<?php echo $val_dia; ?>" size="2"> /
        <input type="text" name="mesNacimiento" placeholder="MM" value="<?php echo $val_mes; ?>" size="2"> /
        <input type="text" name="anyoNacimiento" placeholder="AAAA" value="<?php echo $val_anyo; ?>" size="4">
      </div>

      <label for="ciudad">Ciudad:</label>
      <input type="text" id="ciudad" name="ciudad" value="<?php echo $val_ciudad; ?>">

      <label for="pais">País:</label>
      <select id="pais" name="pais">
        <?php generar_select_options($mysqli, 'paises', 'IdPais', 'NomPais', $val_pais); ?>
      </select>
      
      <label for="estilo">Estilo:</label>
      <select id="estilo" name="estilo">
        <?php generar_select_options($mysqli, 'estilos', 'IdEstilo', 'Nombre', $val_estilo); ?>
      </select>

      <button type="submit">Guardar cambios</button>
    </form>
  
<?php
$mysqli->close();
require_once 'include/footer.php';
?>