<?php
require_once 'include/sesion.php';
require_once 'include/db_connect.php';
require_once 'include/flashdata.inc.php';

controlar_acceso_privado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = conectar_bd();
    $uid = $_SESSION['id_usuario'];
    $clave_input = $_POST['clave_confirmacion'] ?? '';

    // 1. Verificar contraseña
    $stmt = $mysqli->prepare("SELECT Clave FROM usuarios WHERE IdUsuario = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();

    if (!$user || !password_verify($clave_input, $user['Clave'])) {
        set_flashdata('error', "La contraseña es incorrecta. No se ha podido dar de baja.");
        header("Location: baja_usuario.php");
        exit();
    }

    // 2. Borrado manual en cascada (Limpieza completa)
    
    // a) Borrar fotos de los anuncios del usuario
    $sql_del_fotos = "DELETE F FROM fotos F JOIN anuncios A ON F.Anuncio = A.IdAnuncio WHERE A.Usuario = ?";
    $stmt = $mysqli->prepare($sql_del_fotos);
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->close();

    // b) Borrar mensajes relacionados (Origen o Destino)
    $sql_del_msj = "DELETE FROM mensajes WHERE UsuOrigen = ? OR UsuDestino = ?";
    $stmt = $mysqli->prepare($sql_del_msj);
    $stmt->bind_param("ii", $uid, $uid);
    $stmt->execute();
    $stmt->close();

    // --- NUEVO PASO: c) Borrar SOLICITUDES DE FOLLETOS relacionadas con los anuncios del usuario ---
    // Si no borramos esto, la BD bloquea el borrado del anuncio por la Foreign Key
    $sql_del_solic = "DELETE S FROM solicitudes S JOIN anuncios A ON S.Anuncio = A.IdAnuncio WHERE A.Usuario = ?";
    $stmt = $mysqli->prepare($sql_del_solic);
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->close();
    // -----------------------------------------------------------------------------------------------

    // d) Borrar anuncios
    $stmt = $mysqli->prepare("DELETE FROM anuncios WHERE Usuario = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute(); // Ejecutamos y cerramos, si falla lo sabremos al intentar borrar el usuario
    $stmt->close();

    // e) Borrar usuario final
    $stmt = $mysqli->prepare("DELETE FROM usuarios WHERE IdUsuario = ?");
    $stmt->bind_param("i", $uid);
    
    if ($stmt->execute()) {
        // Cerrar sesión y cookies
        session_destroy();
        setcookie('usuario_pi', '', time() - 3600, '/');
        setcookie('clave_pi', '', time() - 3600, '/');
        
        // Redirigir a index con mensaje
        header("Location: index.php?msg=cuenta_eliminada");
    } else {
        // Si falla aquí, mostramos el error real de MySQL para depurar
        set_flashdata('error', "Error al eliminar usuario: " . $stmt->error);
        header("Location: baja_usuario.php");
    }

    $mysqli->close();
    exit();
}
?>