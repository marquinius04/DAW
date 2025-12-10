<?php
require_once 'include/sesion.php';
require_once 'include/db_connect.php';
require_once 'include/flashdata.inc.php';

controlar_acceso_privado();

$mysqli = conectar_bd();
$uid = $_SESSION['id_usuario'];

// 1. Obtener ruta foto actual
$res = $mysqli->query("SELECT Foto FROM usuarios WHERE IdUsuario = $uid");
$row = $res->fetch_assoc();
$foto_actual = $row['Foto'];

// 2. Si no es la default, borrar archivo físico
if ($foto_actual && $foto_actual !== 'img/default_user.jpg') {
    $ruta_fisica = __DIR__ . '/' . $foto_actual;
    if (file_exists($ruta_fisica)) {
        unlink($ruta_fisica);
    }
}

// 3. Actualizar BD a la imagen por defecto
$sql = "UPDATE usuarios SET Foto = 'img/default_user.jpg' WHERE IdUsuario = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $uid);

if ($stmt->execute()) {
    set_flashdata('success', "Foto de perfil eliminada. Se ha restaurado el icono por defecto.");
} else {
    set_flashdata('error', "Error al actualizar perfil.");
}

$mysqli->close();
header("Location: modificar_datos.php");
exit();
?>