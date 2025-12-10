<?php
require_once 'include/sesion.php';
require_once 'include/flashdata.inc.php';
require_once 'include/db_connect.php';
require_once 'include/validaciones.inc.php'; 

$menu_tipo = 'publico'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Recuperación y saneamiento de datos
    $usuario = trim($_POST['usuario'] ?? '');
    $clave1 = $_POST['clave'] ?? '';
    $clave2 = $_POST['clave2'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $sexo = trim($_POST['sexo'] ?? '');
    $diaNac = trim($_POST['diaNacimiento'] ?? '');
    $mesNac = trim($_POST['mesNacimiento'] ?? '');
    $anyoNac = trim($_POST['anyoNacimiento'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');
    $pais_id = (int)($_POST['pais'] ?? 0); 
    
    // Ejecución de validaciones
    $error_mensaje = "";

    if (($msg = validarUsuario($usuario)) !== "") $error_mensaje = $msg;
    elseif (($msg = validarClave($clave1)) !== "") $error_mensaje = $msg;
    elseif (empty($clave2)) $error_mensaje = "Debe repetir la contraseña";
    elseif ($clave1 !== $clave2) $error_mensaje = "Las contraseñas no coinciden";
    elseif (($msg = validarEmail($email)) !== "") $error_mensaje = $msg;
    elseif (empty($sexo)) $error_mensaje = "Debe seleccionar un sexo";
    elseif (($msg = validarFechaNacimiento($diaNac, $mesNac, $anyoNac)) !== "") $error_mensaje = $msg;
    elseif ($pais_id === 0) $error_mensaje = "Debe seleccionar un país válido";
    
    // MANEJO DE ERRORES DE VALIDACIÓN
    if ($error_mensaje !== "") {
        set_flashdata('error', $error_mensaje); 
        $datos_previos = http_build_query($_POST); 
        header("Location: registro.php?{$datos_previos}");
        exit();
    }
    
    // ---------------------------------------------------------
    // 1. GESTIÓN DE SUBIDA DE FOTO (¡NUEVO!)
    // ---------------------------------------------------------
    $foto_ruta = "img/default_user.jpg"; // Valor por defecto si no sube nada

    // Comprobamos si se envió un fichero y si no hubo errores en la subida
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        
        $nombre_original = basename($_FILES['foto']['name']);
        
        // Generamos un nombre único para evitar colisiones (timestamp_nombre)
        // Esto cumple el requisito del PDF: "evitar problemas cuando dos usuarios suban ficheros con el mismo nombre"
        $nombre_unico = time() . "_" . $nombre_original;
        
        // Definimos la ruta de destino
        $ruta_destino = "img/" . $nombre_unico;
        
        // Movemos el fichero de la carpeta temporal a nuestra carpeta de imágenes
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
            $foto_ruta = $ruta_destino;
        } else {
            // Si falla al moverlo, podemos lanzar un error o dejar la foto por defecto
            // En este caso, dejamos la por defecto pero avisamos en el log o flasheamos warning si quisieras.
        }
    }
    // ---------------------------------------------------------

    // 2. INSERCIÓN EN BASE DE DATOS
    
    $mysqli = conectar_bd();

    $clave_hash = password_hash($clave1, PASSWORD_DEFAULT); 
    $estilo_id = 1; 
    $sexo_map = ['Hombre' => 1, 'Mujer' => 0, 'Otro' => 2]; 
    $sexo_db = $sexo_map[$sexo] ?? 2; 
    $fecha_nacimiento_db = "{$anyoNac}-{$mesNac}-{$diaNac}"; 

    $sql = "
        INSERT INTO usuarios 
        (NomUsuario, Clave, Email, Sexo, FNacimiento, Ciudad, Pais, Foto, Estilo) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    
    $stmt = $mysqli->prepare($sql);

    if ($stmt === false) {
        set_flashdata('error', 'Error interno del servidor al preparar el registro.');
        $mysqli->close();
        header("Location: registro.php");
        exit();
    }

    $stmt->bind_param("sssissssi", 
        $usuario, 
        $clave_hash, 
        $email, 
        $sexo_db, 
        $fecha_nacimiento_db, 
        $ciudad, 
        $pais_id, 
        $foto_ruta,  // Usamos la ruta calculada arriba
        $estilo_id
    );

    if ($stmt->execute()) {
        // Éxito
        set_flashdata('success', "¡Registro completado para '{$usuario}'! Foto guardada correctamente.");
        
        $stmt->close();
        $mysqli->close();
        header("Location: index.php");

    } else {
        // Fallo en la BD
        $error_msg = "Error desconocido al registrar.";
        
        if ($stmt->errno === 1062) { // Clave duplicada
            $error_msg = "El nombre de usuario '{$usuario}' ya está registrado.";
        }

        // Si falló la BD, deberíamos borrar la foto que acabamos de subir para no dejar basura
        if ($foto_ruta !== "img/default_user.jpg" && file_exists($foto_ruta)) {
            unlink($foto_ruta);
        }

        set_flashdata('error', "Error al completar el registro: {$error_msg}");
        $datos_previos = http_build_query($_POST); 
        header("Location: registro.php?{$datos_previos}");
    }

    $mysqli->close();
    exit();

} else {
    header("Location: registro.php");
    exit();
}
?>