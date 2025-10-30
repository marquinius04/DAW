<?php
// Fichero: respuesta_mensaje.php

// 1. Redirecciones de validación (deben ir antes del HTML)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Recoger y sanear datos
    $tipo_mensaje = trim($_POST['tipo_mensaje'] ?? ''); // Requisito: Tipo de mensaje válido
    $mensaje_texto = trim($_POST['mensaje_texto'] ?? ''); // Requisito: Texto no vacío
    
    // Recoger, sanear y aplicar trim() al email
    $email_remitente = trim($_POST['email_remitente'] ?? '');
    $anuncio_id = htmlspecialchars($_POST['anuncio_id'] ?? 'N/A');

    $error_mensaje = "";

    // 2. Validación PHP (Requisitos de la práctica + AÑADIDO EL EMAIL)
    
    // VALIDACIÓN 1: EMAIL (Vacío)
    if (empty($email_remitente)) {
        $error_mensaje = "Debe introducir su correo electrónico.";
    } 
    // VALIDACIÓN 2: EMAIL (Formato básico)
    elseif (!filter_var($email_remitente, FILTER_VALIDATE_EMAIL)) {
        $error_mensaje = "El formato del correo electrónico no es válido.";
    }
    // VALIDACIÓN 3: TIPO DE MENSAJE
    elseif (!in_array($tipo_mensaje, ['info', 'cita', 'oferta'])) {
        $error_mensaje = "Debe seleccionar un tipo de mensaje válido.";
    } 
    // VALIDACIÓN 4: TEXTO DEL MENSAJE
    elseif (empty($mensaje_texto)) {
        $error_mensaje = "El cuerpo del mensaje no puede estar vacío.";
    }

    // Si hay un error, redirigir al formulario de mensaje con error en la URL
    if ($error_mensaje !== "") {
        $error_url = urlencode($error_mensaje);
        // Devolvemos el anuncio_id para mantener el contexto
        $id_param = isset($_POST['anuncio_id']) ? "&anuncio_id=" . urlencode($_POST['anuncio_id']) : "";
        header("Location: mensaje.php?error={$error_url}{$id_param}");
        exit();
    }
    
    // --- Lógica de Éxito: Si pasa la validación ---
    
    $titulo_pagina = "Mensaje Enviado";
    // NOTA: Asumo que 'include/head.php' y 'include/footer.php' son las rutas correctas en tu proyecto
    require_once 'include/head.php'; 
    ?>
    
    <main>
        <h2>✅ Mensaje Enviado Correctamente</h2>
        <p>Tu mensaje ha sido enviado al anunciante. A continuación, el resumen de lo que enviaste:</p>
        
        <section style="border: 1px solid #28a745; padding: 15px; background-color: #d4edda;">
            <h3>Resumen del Envío:</h3>
            <ul>
                <li>Anuncio de Referencia (ID): <?php echo $anuncio_id; ?></li>
                <li>Tu Email: <?php echo htmlspecialchars($email_remitente); ?></li>
                <li>Tipo de Mensaje: <?php echo htmlspecialchars($tipo_mensaje); ?></li>
                <li>Contenido del Mensaje: <p style="white-space: pre-wrap; margin-top: 5px; background-color: white; padding: 5px; border: 1px dashed #ccc;"><?php echo htmlspecialchars($mensaje_texto); ?></p></li>
            </ul>
        </section>

        <p>Gracias por tu mensaje, el anunciante se pondrá en contacto contigo pronto.</p>
        <a href="index_logueado.php">Volver al inicio</a>
    </main>
    
    <?php
    require_once 'include/footer.php';
    exit();
} else {
    // Si se accede directamente sin POST, redirigir al formulario
    header("Location: mensaje.php");
    exit();
}
?>