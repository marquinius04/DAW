<?php
require_once 'include/sesion.php'; 
require_once 'include/flashdata.inc.php'; 
require_once 'include/db_connect.php';      
require_once 'include/validaciones.inc.php'; 

controlar_acceso_privado();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: modificar_datos.php");
    exit();
}

$mysqli = conectar_bd();
$id_usuario = $_SESSION['id_usuario'];
$error_mensaje = "";

// RECOGIDA DE DATOS DEL FORMULARIO
$clave_actual_input = $_POST['clave_actual'] ?? '';
$nueva_clave_input = $_POST['nueva_clave'] ?? '';
$nueva_clave2_input = $_POST['nueva_clave2'] ?? '';

// Datos modificables
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$sexo = (int)($_POST['sexo'] ?? 0);
$diaNac = trim($_POST['diaNacimiento'] ?? '');
$mesNac = trim($_POST['mesNacimiento'] ?? '');
$anyoNac = trim($_POST['anyoNacimiento'] ?? '');
$ciudad = filter_var(trim($_POST['ciudad'] ?? ''), FILTER_SANITIZE_STRING);
$pais_id = (int)($_POST['pais'] ?? 0); 
$estilo_id = (int)($_POST['estilo'] ?? 0); 



if (empty($clave_actual_input)) {
    $error_mensaje = "Debe introducir su contraseña actual para confirmar cualquier cambio.";
    goto handle_error;
}

// Obtener el hash actual del usuario desde la BD
$sql_hash = "SELECT Clave FROM usuarios WHERE IdUsuario = ?";
$stmt_hash = $mysqli->prepare($sql_hash);
if ($stmt_hash === false) {
    $error_mensaje = "Error al preparar la consulta de contraseña: " . $mysqli->error;
    goto handle_error;
}
$stmt_hash->bind_param("i", $id_usuario);
if (!$stmt_hash->execute()) {
    $error_mensaje = "Error al ejecutar la consulta de contraseña: " . $stmt_hash->error;
    $stmt_hash->close();
    goto handle_error;
}

// Obtener resultado de forma compatible: usar get_result() si está disponible, si no bind_result()
$hash_actual_db = null;
if (method_exists($stmt_hash, 'get_result')) {
    $resultado_hash = $stmt_hash->get_result();
    $hash_actual_db = $resultado_hash->fetch_assoc()['Clave'] ?? null;
} else {
    $stmt_hash->bind_result($hash_actual_db);
    $stmt_hash->fetch();
}
$stmt_hash->close();

if (!$hash_actual_db || !password_verify($clave_actual_input, $hash_actual_db)) {
    $error_mensaje = "La contraseña actual es incorrecta. No se han guardado los cambios.";
    goto handle_error;
}


// Validación de la Nueva Contraseña (solo si se intenta cambiar)
$nueva_clave_hash = null;
if (!empty($nueva_clave_input)) {
    if (($msg = validarClave($nueva_clave_input)) !== "") $error_mensaje = $msg;
    elseif ($nueva_clave_input !== $nueva_clave2_input) $error_mensaje = "La nueva contraseña no coincide con la repetición.";
    
    if ($error_mensaje !== "") goto handle_error;
    
    // Generar el hash de la nueva clave
    $nueva_clave_hash = password_hash($nueva_clave_input, PASSWORD_DEFAULT);
}

// Validación de Email y Fecha
if (($msg = validarEmail($email)) !== "") $error_mensaje = $msg;
elseif (($msg = validarFechaNacimiento($diaNac, $mesNac, $anyoNac)) !== "") $error_mensaje = $msg;

// Validación de Claves Ajena (que no estén vacías si son obligatorias)
elseif ($pais_id === 0) $error_mensaje = "Debe seleccionar un país válido.";
elseif ($estilo_id === 0) $error_mensaje = "Debe seleccionar un estilo válido.";

if ($error_mensaje !== "") goto handle_error;



$fields = [];
$types = "";
$params = [];

// Campos que siempre se actualizan 
// En la BD, el Sexo está mapeado 1=H, 0=M, 2=Otro (se mapea en modificar_datos.php)
$fields[] = "Email = ?"; $types .= "s"; $params[] = $email;
$fields[] = "Sexo = ?"; $types .= "i"; $params[] = $sexo;
$fecha_nac = sprintf('%04d-%02d-%02d', (int)$anyoNac, (int)$mesNac, (int)$diaNac);
$fields[] = "FNacimiento = ?"; $types .= "s"; $params[] = $fecha_nac;
$fields[] = "Ciudad = ?"; $types .= "s"; $params[] = $ciudad;
$fields[] = "Pais = ?"; $types .= "i"; $params[] = $pais_id;
$fields[] = "Estilo = ?"; $types .= "i"; $params[] = $estilo_id;

// Si se proporcionó una nueva clave, añadirla al UPDATE
if ($nueva_clave_hash !== null) {
    $fields[] = "Clave = ?"; 
    $types .= "s"; 
    $params[] = $nueva_clave_hash; 
}

if (empty($fields)) {
    $error_mensaje = "No se detectaron campos para modificar.";
    goto handle_error;
}

// Agregar el ID del usuario al final de los parámetros para la cláusula WHERE
$types .= "i";
$params[] = $id_usuario;

$sql_update = "UPDATE usuarios SET " . implode(", ", $fields) . " WHERE IdUsuario = ?";
$stmt_update = $mysqli->prepare($sql_update);

if ($stmt_update === false) {
    $error_mensaje = "Error al preparar la sentencia de actualización: " . $mysqli->error;
    goto handle_error;
}

// Llamada dinámica a bind_param 
$bind_args = [];
$bind_args[] = $types;
foreach ($params as $key => $value) {
    // bind_param requiere referencias a las variables
    $bind_args[] = &$params[$key];
}
call_user_func_array([$stmt_update, 'bind_param'], $bind_args);

if ($stmt_update->execute()) {
    $filas_afectadas = $stmt_update->affected_rows;
    
    // Si la contraseña fue actualizada, se invalida la cookie de "recuerdame"
    if ($nueva_clave_hash !== null) {
        setcookie('usuario_pi', '', time() - 3600, '/');
        setcookie('clave_pi', '', time() - 3600, '/');
        setcookie('ultima_visita_real', '', time() - 3600, '/');
        $mensaje_final = "Datos actualizados. La contraseña fue modificada, debe iniciar sesión de nuevo.";
        
    } elseif ($filas_afectadas > 0) {
        $mensaje_final = "Datos actualizados correctamente.";
        
    } else {
        $mensaje_final = "Ningún dato ha sido modificado.";
    }

    // Actualizar la variable de sesión con el nuevo ID de estilo
    $_SESSION['estilo'] = $estilo_id;

    set_flashdata('success', $mensaje_final);

    $stmt_update->close();
    $mysqli->close();
    header("Location: modificar_datos.php");
    exit();

} else {
    $error_mensaje = "Error al ejecutar la actualización: " . $stmt_update->error;
    
    if ($stmt_update->errno === 1062) {
         $error_mensaje = "El correo electrónico introducido ya está en uso. Por favor, utilice otro.";
    }
    $stmt_update->close();
    goto handle_error;
}



handle_error: 
if ($mysqli) $mysqli->close();

set_flashdata('error', $error_mensaje);

$safe_post = $_POST;
unset($safe_post['clave_actual'], $safe_post['nueva_clave'], $safe_post['nueva_clave2']);
$datos_previos = http_build_query($safe_post);
header("Location: modificar_datos.php?{$datos_previos}");
exit();
?>