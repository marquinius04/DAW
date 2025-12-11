<?php
require_once 'include/sesion.php';
require_once 'include/db_connect.php';
require_once 'include/flashdata.inc.php';

controlar_acceso_privado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = conectar_bd();
    $uid = $_SESSION['id_usuario'];
    $clave_input = $_POST['clave_confirmacion'] ?? '';

    // Verificar contraseña
    $stmt = $mysqli->prepare("SELECT Clave, Foto FROM usuarios WHERE IdUsuario = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();

    if (!$user || !password_verify($clave_input, $user['Clave'])) {
        set_flashdata('error', "Contraseña incorrecta. Baja cancelada.");
        header("Location: baja_usuario.php");
        exit();
    }

    // Borrado físico de fotos de anuncios
    // Obtenemos todas las rutas de fotos de los anuncios de este usuario
    $sql_fotos = "SELECT F.Foto 
                  FROM fotos F 
                  JOIN anuncios A ON F.Anuncio = A.IdAnuncio 
                  WHERE A.Usuario = ?";
    
    $stmt_f = $mysqli->prepare($sql_fotos);
    $stmt_f->bind_param("i", $uid);
    $stmt_f->execute();
    $res_f = $stmt_f->get_result();
    
    while ($row = $res_f->fetch_assoc()) {
        $ruta = $row['Foto'];
        if (!empty($ruta) && file_exists(__DIR__ . '/' . $ruta)) {
            unlink(__DIR__ . '/' . $ruta);
        }
    }
    $stmt_f->close();
    
    // Borrado físico de Fotos Principales de anuncios 
    $sql_main = "SELECT FPrincipal FROM anuncios WHERE Usuario = ?";
    $stmt_m = $mysqli->prepare($sql_main);
    $stmt_m->bind_param("i", $uid);
    $stmt_m->execute();
    $res_m = $stmt_m->get_result();
    while ($row = $res_m->fetch_assoc()) {
        $ruta = $row['FPrincipal'];
        if (!empty($ruta) && $ruta !== 'img/default.jpg' && file_exists(__DIR__ . '/' . $ruta)) {
            unlink(__DIR__ . '/' . $ruta);
        }
    }
    $stmt_m->close();

    // Borrado físico de foto de perfil
    $foto_perfil = $user['Foto'];
    if ($foto_perfil && $foto_perfil !== 'img/default_user.jpg' && file_exists(__DIR__ . '/' . $foto_perfil)) {
        unlink(__DIR__ . '/' . $foto_perfil);
    }

    // Borrado de datos en BD 
    
    // Borrar fotos de anuncios
    $mysqli->query("DELETE F FROM fotos F JOIN anuncios A ON F.Anuncio = A.IdAnuncio WHERE A.Usuario = $uid");
    
    // Borrar mensajes (enviados y recibidos)
    $mysqli->query("DELETE FROM mensajes WHERE UsuOrigen = $uid OR UsuDestino = $uid");
    
    // Borrar solicitudes de folletos
    $mysqli->query("DELETE S FROM solicitudes S JOIN anuncios A ON S.Anuncio = A.IdAnuncio WHERE A.Usuario = $uid");

    // Borrar anuncios
    $mysqli->query("DELETE FROM anuncios WHERE Usuario = $uid");

    // Borrar usuario
    $del_user = $mysqli->prepare("DELETE FROM usuarios WHERE IdUsuario = ?");
    $del_user->bind_param("i", $uid);
    
    if ($del_user->execute()) {
        // Cerrar sesión
        session_destroy();
        // Borrar cookies
        setcookie('usuario_pi', '', time() - 3600, '/');
        setcookie('clave_pi', '', time() - 3600, '/');
        
        header("Location: index.php?msg=cuenta_borrada");
    } else {
        set_flashdata('error', "Error BD al eliminar usuario.");
        header("Location: baja_usuario.php");
    }

    $mysqli->close();
    exit();
}
?>