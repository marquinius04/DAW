<?php

$titulo_pagina = "Mis datos - PI";
$body_id = "modificarDatosPage"; 
$menu_tipo = 'privado'; 

// INCLUSIONES Y CONTROL DE ACCESO
require_once 'include/head.php'; 
require_once 'include/db_connect.php';
require_once 'include/select_options.inc.php';
require_once 'include/flashdata.inc.php'; 

controlar_acceso_privado();

// OBTENER DATOS DEL USUARIO LOGUEADO
$mysqli = conectar_bd();
$id_usuario = $_SESSION['id_usuario']; // Obtenemos el ID de sesión

// Consulta para obtener todos los datos necesarios del usuario y su estilo actual
$sql_usuario = "
    SELECT 
        U.NomUsuario, U.Clave, U.Email, U.Sexo, U.FNacimiento, U.Ciudad, U.Pais, U.Estilo, E.Nombre
    FROM 
        usuarios U
    JOIN 
        estilos E ON U.Estilo = E.IdEstilo
    WHERE 
        U.IdUsuario = ?
";

$stmt = $mysqli->prepare($sql_usuario);

if ($stmt === false) {
    die("Error al preparar la consulta de usuario: " . $mysqli->error);
}

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$datos_usuario = $resultado->fetch_assoc();

if (!$datos_usuario) {
    header("Location: logout.php");
    exit();
}

$stmt->close();

// PREPARACIÓN DE VARIABLES PARA EL FORMULARIO
$val_usuario = htmlspecialchars($datos_usuario['NomUsuario']);
$val_email = htmlspecialchars($datos_usuario['Email']);
$val_sexo = $datos_usuario['Sexo']; // 1=Hombre, 0=Mujer, 2=Otro 
$val_ciudad = htmlspecialchars($datos_usuario['Ciudad']);
$val_pais = $datos_usuario['Pais']; 
$val_estilo = $datos_usuario['Estilo'];

// Formato de fecha para repoblar los campos DD, MM, AAAA
$fecha_nacimiento = new DateTime($datos_usuario['FNacimiento']);
$val_dia = $fecha_nacimiento->format('d');
$val_mes = $fecha_nacimiento->format('m');
$val_anyo = $fecha_nacimiento->format('Y');

// Mensajes flash (éxito o error de la modificación anterior)
$flash_success = get_flashdata('success');
$flash_error = get_flashdata('error');

?>

    <h2>Modificar mis datos de usuario</h2>

    <?php if ($flash_success): ?>
        <p style="color: green; border: 1px solid green; padding: 10px; background-color: #ffeaea; margin-top: 15px; margin-bottom: 15px;">
            ✅ Éxito: <?php echo htmlspecialchars($flash_success); ?>
        </p>
    <?php endif; ?>
    
    <?php if ($flash_error): ?>
        <p style="color: red; border: 1px solid red; padding: 10px; background-color: #ffeaea; margin-top: 15px; margin-bottom: 15px;">
            ⚠️ Error: <?php echo htmlspecialchars($flash_error); ?>
        </p>
    <?php endif; ?>

    <form action="respuesta_mis_datos.php" method="post">
        
      <label for="usuario">Nombre de usuario:</label>
      <input type="text" id="usuario" name="usuario" value="<?php echo $val_usuario; ?>" readonly>
      <p class="nota">El nombre de usuario no puede modificarse.</p>

      <label for="clave_actual">(*) Contraseña actual (Para confirmar cualquier cambio):</label>
      <input type="password" id="clave_actual" name="clave_actual">
      <span id="claveActualError" class="error"></span>

      <hr style="margin: 20px 0; border-top: 1px dashed #ccc;">
      
      <label for="nueva_clave">Nueva contraseña:</label>
      <input type="password" id="nueva_clave" name="nueva_clave" placeholder="Dejar vacío si no desea cambiarla">
      <span id="nuevaClaveError" class="error"></span> 

      <label for="nueva_clave2">Repetir nueva contraseña:</label>
      <input type="password" id="nueva_clave2" name="nueva_clave2">
      <span id="nuevaClave2Error" class="error"></span> 
      
      <hr style="margin: 20px 0; border-top: 1px dashed #ccc;">
      
      <label for="email">(*) Correo electrónico:</label>
      <input type="text" id="email" name="email" value="<?php echo $val_email; ?>">
      <span id="emailError" class="error"></span> 

      <label>Sexo:</label>
      <select name="sexo" id="sexo">
        <option value="">-- Seleccione una opción --</option>
        <option value="1" <?php echo ($val_sexo == 1) ? 'selected' : ''; ?>>Hombre</option>
        <option value="0" <?php echo ($val_sexo == 0) ? 'selected' : ''; ?>>Mujer</option>
        <option value="2" <?php echo ($val_sexo == 2) ? 'selected' : ''; ?>>Prefiero no contestar</option>
      </select>
      <span id="sexoError" class="error"></span> 

      <label for="nacimiento">Fecha de nacimiento:</label>
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
        // Rellenar con datos de la BD (tabla PAISES) y preseleccionar el valor actual
        generar_select_options($mysqli, 'paises', 'IdPais', 'NomPais', $val_pais);
        ?>
        </select>
      <span id="paisError" class="error"></span> 
      
      <label for="estilo">Estilo de la interfaz:</label>
      <select id="estilo" name="estilo">
        <option value="">-- Seleccione un estilo --</option>
        <?php
        // Rellenar con datos de la tabla ESTILOS y preseleccionar el valor actual
        generar_select_options($mysqli, 'estilos', 'IdEstilo', 'Nombre', $val_estilo);
        ?>
        </select>
      <span id="estiloError" class="error"></span> 


      <button type="submit" name="modificar">Guardar cambios</button>
    </form>
  
<?php
$mysqli->close(); // Cierra la conexión a la BD
require_once 'include/footer.php';
?>