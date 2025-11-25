<?php
$titulo_pagina = "Mis mensajes - PI";
require_once 'include/head.php'; 
require_once 'include/db_connect.php';
controlar_acceso_privado(); 
$mysqli = conectar_bd();

$usuario_nom = $_SESSION['usuario'];
// Obtener ID
$res_u = $mysqli->query("SELECT IdUsuario FROM usuarios WHERE NomUsuario = '$usuario_nom'");
$id_usuario = $res_u->fetch_assoc()['IdUsuario'];

// Consultar RECIBIDOS
$sql_rec = "SELECT M.*, U.NomUsuario as Remitente, TM.NomTMensaje 
            FROM mensajes M 
            JOIN usuarios U ON M.UsuOrigen = U.IdUsuario 
            JOIN tiposmensajes TM ON M.TMensaje = TM.IdTMensaje
            WHERE UsuDestino = $id_usuario";
$res_rec = $mysqli->query($sql_rec);

// Consultar ENVIADOS
$sql_env = "SELECT M.*, U.NomUsuario as Destinatario, TM.NomTMensaje 
            FROM mensajes M 
            JOIN usuarios U ON M.UsuDestino = U.IdUsuario 
            JOIN tiposmensajes TM ON M.TMensaje = TM.IdTMensaje
            WHERE UsuOrigen = $id_usuario";
$res_env = $mysqli->query($sql_env);
?>

<h2>Mis mensajes</h2>

<h3>Recibidos (<?= $res_rec->num_rows ?>)</h3>
<table>
    <thead><tr><th>De</th><th>Tipo</th><th>Texto</th><th>Fecha</th></tr></thead>
    <tbody>
    <?php while($m = $res_rec->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($m['Remitente']) ?></td>
            <td><?= htmlspecialchars($m['NomTMensaje']) ?></td>
            <td><?= htmlspecialchars($m['Texto']) ?></td>
            <td><?= $m['FRegistro'] ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<h3>Enviados (<?= $res_env->num_rows ?>)</h3>
<table>
    <thead><tr><th>Para</th><th>Tipo</th><th>Texto</th><th>Fecha</th></tr></thead>
    <tbody>
    <?php while($m = $res_env->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($m['Destinatario']) ?></td>
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