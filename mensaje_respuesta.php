<?php
require_once 'include/sesion.php';
require_once 'include/db_connect.php';
require_once 'include/flashdata.inc.php';

controlar_acceso_privado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = conectar_bd();

    $tipo_mensaje_str = trim($_POST['tipo_mensaje'] ?? ''); 
    $mensaje_texto = trim($_POST['mensaje_texto'] ?? ''); 
    $email_remitente = trim($_POST['email_remitente'] ?? '');
    $anuncio_id = (int)($_POST['anuncio_id'] ?? 0);
    $usu_origen = $_SESSION['id_usuario'];

    // --- Validaciones ---
    if (empty($email_remitente) || empty($mensaje_texto) || $anuncio_id <= 0) {
        set_flashdata('error', "Todos los campos son obligatorios.");
        header("Location: mensaje.php?anuncio_id=$anuncio_id");
        exit();
    }

    // Obtener ID del Usuario Destino 
    $sql_dest = "SELECT Usuario FROM anuncios WHERE IdAnuncio = $anuncio_id";
    $res_dest = $mysqli->query($sql_dest);
    if ($res_dest->num_rows === 0) {
        set_flashdata('error', "El anuncio no existe.");
        header("Location: index.php");
        exit();
    }
    $usu_destino = $res_dest->fetch_assoc()['Usuario'];

    
    // Buscamos el ID en la BD basado en el nombre enviado
    $stmt_tipo = $mysqli->prepare("SELECT IdTMensaje FROM tiposmensajes WHERE NomTMensaje = ?");
    $stmt_tipo->bind_param("s", $tipo_mensaje_str);
    $stmt_tipo->execute();
    $res_tipo = $stmt_tipo->get_result();
    
    if ($row = $res_tipo->fetch_assoc()) {
        $id_tmensaje = $row['IdTMensaje'];
    } else {
        // Fallback por defecto si no coincide
        $id_tmensaje = 1; 
    }
    $stmt_tipo->close();

    // INSERTAR MENSAJE
    $sql_insert = "INSERT INTO mensajes (TMensaje, Texto, Anuncio, UsuOrigen, UsuDestino) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql_insert);
    $stmt->bind_param("isiii", $id_tmensaje, $mensaje_texto, $anuncio_id, $usu_origen, $usu_destino);

    if ($stmt->execute()) {
        $titulo_pagina = "Mensaje Enviado";
        require_once 'include/head.php'; 
        ?>
        <main>
            <h2><span class="icono">send</span> Mensaje enviado con éxito</h2>
            <p>El propietario del anuncio recibirá tu mensaje en su buzón privado.</p>
            <div style="background: #f0f0f0; padding: 15px; border-radius: 5px;">
                <p><strong>Para:</strong> Usuario #<?= $usu_destino ?></p>
                <p><strong>Mensaje:</strong> <?= htmlspecialchars($mensaje_texto) ?></p>
            </div>
            <br>
            <a href="aviso.php?id=<?= $anuncio_id ?>" class="btn-contacto" style="width: auto; display: inline-block;">Volver al anuncio</a>
        </main>
        <?php
        require_once 'include/footer.php';
    } else {
        set_flashdata('error', "Error al enviar mensaje: " . $stmt->error);
        header("Location: mensaje.php?anuncio_id=$anuncio_id");
    }

    $stmt->close();
    $mysqli->close();
    exit();
}
?>