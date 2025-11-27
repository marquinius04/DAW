<?php
require_once 'include/sesion.php'; 
require_once 'include/db_connect.php';
require_once 'include/flashdata.inc.php';

controlar_acceso_privado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_estilo = (int)$_POST['estilo'];
    $usuario = $_SESSION['usuario'];
    
    $mysqli = conectar_bd();
    
    // Actualizar en la base de datos
    $stmt = $mysqli->prepare("UPDATE usuarios SET Estilo = ? WHERE NomUsuario = ?");
    $stmt->bind_param("is", $nuevo_estilo, $usuario);
    
    if ($stmt->execute()) {
        // Actualizar la sesión actual para ver el cambio inmediatamente
        // Buscamos el nombre del fichero CSS asociado al ID seleccionado
        $res = $mysqli->query("SELECT Fichero FROM estilos WHERE IdEstilo = $nuevo_estilo");
        if ($row = $res->fetch_assoc()) {
            $_SESSION['estilo'] = $row['Fichero'];
        }
        
        set_flashdata('success', "Estilo actualizado correctamente.");
    } else {
        set_flashdata('error', "Error al actualizar el estilo.");
    }
    
    $stmt->close();
    $mysqli->close();
    
    header("Location: index_logueado.php");
    exit();
} else {
    header("Location: configurar.php");
    exit();
}
?>