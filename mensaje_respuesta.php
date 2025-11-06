<?php
require_once 'include/sesion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    controlar_acceso_privado();

    $tipo_mensaje = trim($_POST['tipo_mensaje'] ?? ''); 
    $mensaje_texto = trim($_POST['mensaje_texto'] ?? ''); 
    $email_remitente = trim($_POST['email_remitente'] ?? '');
    $anuncio_id = htmlspecialchars($_POST['anuncio_id'] ?? 'N/A');

    $error_mensaje = "";

    // Validación 
    if (empty($email_remitente)) {
        $error_mensaje = "Debe introducir su correo electrónico.";
    } elseif (!filter_var($email_remitente, FILTER_VALIDATE_EMAIL)) {
        $error_mensaje = "El formato del correo electrónico no es válido.";
    } elseif (!in_array($tipo_mensaje, ['info', 'cita', 'oferta'])) {
        $error_mensaje = "Debe seleccionar un tipo de mensaje válido.";
    } elseif (empty($mensaje_texto)) {
        $error_mensaje = "El cuerpo del mensaje no puede estar vacío.";
    }

    // Si hay un error, redirigir al formulario de mensaje con error en la URL
    if ($error_mensaje !== "") {
        // Usamos flashdata 
        $_SESSION['flash_error'] = $error_mensaje;
        $id_param = isset($_POST['anuncio_id']) ? "anuncio_id=" . urlencode($_POST['anuncio_id']) : "";
        header("Location: mensaje.php?{$id_param}");
        exit();
    }
    
    $titulo_pagina = "Mensaje enviado";
    require_once 'include/head.php'; 
    ?>
    
    <main>
        <h2><span class="icono">check_circle</span> Mensaje enviado correctamente</h2>
        <p>Tu mensaje ha sido enviado al anunciante. A continuación, el resumen de lo que enviaste:</p>
        
        <section class="caja-lateral" style="background-color: #f0f7ff; border: 1px solid var(--color-primario); text-align: center; padding-bottom: 20px;">
            
            <h3>Resumen del envío:</h3>
            
            <p style="margin-bottom: 0.75em;">ID del anuncio: <strong><?php echo $anuncio_id; ?></strong></p>
            
            <p style="margin-bottom: 0.75em;">Tu email: <strong><?php echo htmlspecialchars($email_remitente); ?></strong></p>
            
            <p style="margin-bottom: 1.5em;">Tipo de mensaje: <strong><?php echo htmlspecialchars($tipo_mensaje); ?></strong></p>
            
            <p style="margin-bottom: 0.5em;">Contenido del mensaje:</p>
            
            <div style="white-space: pre-wrap; 
                        background-color: #f8f9fa; 
                        border: 1px solid #ddd; 
                        padding: 12px; 
                        border-radius: 4px; 
                        font-weight: bold;   /* Tu petición de negrita */
                        text-align: left;    /* El texto del mensaje empieza a la izquierda */
                        display: block;      /* Lo tratamos como un bloque */
                        margin: 0 auto;    /* Lo centramos con márgenes automáticos */
                        min-width: 300px;
                        max-width: 90%;">
                <?php echo htmlspecialchars($mensaje_texto); ?>
            </div>

        </section>

        <p>Gracias por tu mensaje, el anunciante se pondrá en contacto contigo pronto.</p>
        
        <a href="index_logueado.php" style="display: inline-block; background-color: var(--color-primario); color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-top: 15px; font-weight: bold;">
            <span class="icono">home</span> Volver al inicio
        </a>
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