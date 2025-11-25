<?php
$titulo_pagina = "Mensajes del Anuncio - PI";
require_once 'include/head.php'; 
require_once 'include/db_connect.php';
controlar_acceso_privado(); 

$id_anuncio = (int)$_GET['id'];
$mysqli = conectar_bd();

// Verificar que el anuncio pertenece al usuario logueado
$nom = $_SESSION['usuario'];
$check = $mysqli->query("SELECT A.IdAnuncio, A.Titulo FROM anuncios A JOIN usuarios U ON A.Usuario = U.IdUsuario WHERE A.IdAnuncio = $id_anuncio AND U.NomUsuario = '$nom'");

if ($check->num_rows === 0) {
    die("No tienes permiso para ver mensajes de este anuncio.");
}
$datos_anuncio = $check->fetch_assoc();

// Obtener mensajes recibidos para este anuncio
$sql = "SELECT M.*, TM.NomTMensaje, U.NomUsuario as Remitente 
        FROM mensajes M 
        JOIN tiposmensajes TM ON M.TMensaje = TM.IdTMensaje
        JOIN usuarios U ON M.UsuOrigen = U.IdUsuario
        WHERE M.Anuncio = $id_anuncio";
$res = $mysqli->query($sql);
?>

<h2>Mensajes para: <?= htmlspecialchars($datos_anuncio['Titulo']) ?></h2>
<p>Total mensajes: <?= $res->num_rows ?></p>

<table>
    <thead><tr><th>Remitente</th><th>Tipo</th><th>Mensaje</th><th>Fecha</th></tr></thead>
    <tbody>
    <?php while($m = $res->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($m['Remitente']) ?></td>
            <td><?= htmlspecialchars($m['NomTMensaje']) ?></td>
            <td><?= htmlspecialchars($m['Texto']) ?></td>
            <td><?= $m['FRegistro'] ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>