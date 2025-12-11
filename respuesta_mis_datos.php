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

// Verificar contraseña actual
$clave_actual = $_POST['clave_actual'] ?? '';
$sql = "SELECT Clave, Foto FROM usuarios WHERE IdUsuario = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res = $stmt->get_result();
$user_data = $res->fetch_assoc();
$stmt->close();

if (!$user_data || !password_verify($clave_actual, $user_data['Clave'])) {
    set_flashdata('error', "Contraseña actual incorrecta.");
    header("Location: modificar_datos.php");
    exit();
}

// Recogida de datos generales
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$sexo = (int)$_POST['sexo'];
$fecha_nac = $_POST['anyoNacimiento'] . "-" . $_POST['mesNacimiento'] . "-" . $_POST['diaNacimiento'];
$ciudad = filter_var($_POST['ciudad'], FILTER_SANITIZE_STRING);
$pais = (int)$_POST['pais'];
$estilo = (int)$_POST['estilo'];
$nueva_clave = $_POST['nueva_clave'];

// Validaciones simples 
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error_mensaje = "Email inválido.";

if ($error_mensaje) {
    set_flashdata('error', $error_mensaje);
    header("Location: modificar_datos.php");
    exit();
}

// Gestión de foto de perfil 
$nueva_ruta_foto = null; // Si se mantiene null, no se actualiza este campo

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    // Subir nueva foto
    $nombre_original = basename($_FILES['foto']['name']);
    $nombre_unico = time() . "_pfp_" . $nombre_original; // Evitar colisiones
    $ruta_destino = "img/" . $nombre_unico;
    
    if (move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . '/' . $ruta_destino)) {
        $nueva_ruta_foto = $ruta_destino;
        
        // Borrar foto antigua si existe y no es la default
        $foto_antigua = $user_data['Foto'];
        if ($foto_antigua && $foto_antigua !== 'img/default_user.jpg' && file_exists(__DIR__ . '/' . $foto_antigua)) {
            unlink(__DIR__ . '/' . $foto_antigua);
        }
    } else {
        set_flashdata('error', "Error al mover la foto subida.");
        header("Location: modificar_datos.php");
        exit();
    }
}

// Construcción de la consulta UPDATE dinámica
$sql_update = "UPDATE usuarios SET Email=?, Sexo=?, FNacimiento=?, Ciudad=?, Pais=?, Estilo=?";
$params = [$email, $sexo, $fecha_nac, $ciudad, $pais, $estilo];
$types = "sisssi";

// Añadir Clave si cambió
if (!empty($nueva_clave)) {
    $sql_update .= ", Clave=?";
    $params[] = password_hash($nueva_clave, PASSWORD_DEFAULT);
    $types .= "s";
}

// Añadir Foto si cambió
if ($nueva_ruta_foto !== null) {
    $sql_update .= ", Foto=?";
    $params[] = $nueva_ruta_foto;
    $types .= "s";
}

$sql_update .= " WHERE IdUsuario=?";
$params[] = $id_usuario;
$types .= "i";

// Ejecutar
$stmt = $mysqli->prepare($sql_update);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    $_SESSION['estilo'] = $estilo; // Actualizar sesión
    set_flashdata('success', "Datos modificados correctamente.");
} else {
    set_flashdata('error', "Error al actualizar BD: " . $stmt->error);
}

$stmt->close();
$mysqli->close();
header("Location: modificar_datos.php");
exit();
?>