<?php
// Fichero: respuesta_folleto.php

// Las redirecciones DEBEN hacerse antes de cualquier contenido HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- INICIO DE LA VALIDACIÓN (REQUISITO FALTANTE) ---
    
    // 1. Recolección de datos (con trim() para validar campos vacíos)
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $calle = trim($_POST['calle'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $codigo_postal = trim($_POST['codigo_postal'] ?? '');
    $localidad = trim($_POST['localidad'] ?? '');
    $provincia = trim($_POST['provincia'] ?? '');
    $anuncio = trim($_POST['anuncio'] ?? '');
    
    // Recoger el resto de datos para la página de éxito
    $num_copias = (int)($_POST['num_copias'] ?? 1);
    $resolucion = (int)($_POST['resolucion'] ?? 150);
    $impresion_precio = htmlspecialchars($_POST['impresion_precio'] ?? 'con_precio'); 
    $impresion_color = htmlspecialchars($_POST['impresion_color'] ?? 'blanco_negro');
    
    $error_mensaje = "";

    // 2. Validación de campos obligatorios (*)
    // Comprobamos los campos uno por uno
    if (empty($nombre)) {
        $error_mensaje = "El nombre y apellidos son obligatorios.";
    } elseif (empty($email)) {
        $error_mensaje = "El correo electrónico es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
        // Validación extra para asegurar un email con formato correcto
        $error_mensaje = "El formato del correo electrónico no es válido.";
    } elseif (empty($calle)) {
        $error_mensaje = "La calle es obligatoria.";
    } elseif (empty($numero)) {
        $error_mensaje = "El número de la dirección es obligatorio.";
    } elseif (empty($codigo_postal)) {
        $error_mensaje = "El código postal es obligatorio.";
    } elseif (empty($localidad)) {
        $error_mensaje = "La localidad es obligatoria.";
    } elseif (empty($provincia)) {
        $error_mensaje = "La provincia es obligatoria.";
    } elseif (empty($anuncio)) { 
        // El select de "anuncio" tiene un valor por defecto ""
        $error_mensaje = "Debe seleccionar un anuncio para imprimir.";
    }
    
    // 3. Redirección si hay error
    if ($error_mensaje !== "") {
        $error_url = urlencode($error_mensaje);
        // Redirigimos de vuelta al formulario con el mensaje de error
        header("Location: folleto.php?error={$error_url}");
        exit();
    }
    
    // --- FIN DE LA VALIDACIÓN ---
    
    
    // --- LÓGICA DE ÉXITO (Si pasa la validación) ---
    
    // Si no hay error, incluimos la cabecera y mostramos la página de éxito
    $titulo_pagina = "Confirmación de solicitud de folleto - PI";
    require_once 'include/head.php'; 
    
    // 2. Requisito: Valores FICTICIOS para Páginas y Fotos (para el resumen)
    $PAGINAS_FICTICIAS = 15; 
    $FOTOS_FICTICIAS = 45;   
    
    // 3. Cálculo del Coste Final
    $costeFijo = 2;
    $precioUnidad = 0;
    $es_color = ($impresion_color === "color"); 

    // Se asume 150 DPI para la tarifa baja (4 págs.) y >150 DPI para la tarifa alta (8 págs.)
    if ($resolucion === 150) {
        $precioUnidad = $es_color ? 7 : 5; // Tarifa 4 págs.
    } else {
        $precioUnidad = $es_color ? 12 : 8; // Tarifa 8 págs.
    }
    
    $total = ($precioUnidad * $num_copias) + $costeFijo;

    ?>
    
    <main>
        <h2>✅ Solicitud enviada correctamente</h2>
        <p>Gracias por su solicitud. A continuación se muestra el **resumen del pedido y el coste final**:</p>

        <section id="resumen" style="border: 1px solid #0056b3; padding: 15px; background-color: #f0f7ff;">
            <h3>Resumen de la Solicitud Procesada</h3>
            <ul>
                <li>Nombre: <?php echo htmlspecialchars($nombre); ?></li>
                <li>Email: <?php echo htmlspecialchars($email); ?></li>
                <li>Dirección: <?php echo htmlspecialchars("$calle, $numero, $codigo_postal, $localidad, $provincia"); ?></li>
                <li>Anuncio: <?php echo htmlspecialchars($anuncio); ?></li>
                <li>Copias Solicitadas: <?php echo $num_copias; ?></li>
                <li>Resolución de Fotos: <?php echo $resolucion; ?> DPI</li>
                <li>Tipo de Impresión: <strong><?php echo $es_color ? "Color" : "Blanco y Negro"; ?></strong></li>
                <li>Impresión de Precio: <?php echo $impresion_precio === "con_precio" ? "Sí" : "No"; ?></li>
                <li>Páginas (EJEMPLO): <?php echo $PAGINAS_FICTICIAS; ?></li>
                <li>Fotos (EJEMPLO): <?php echo $FOTOS_FICTICIAS; ?></li>
                <li>Precio Unitario (calculado): <?php echo $precioUnidad; ?> €</li>
                <li>Coste Fijo de Envío: <?php echo $costeFijo; ?> €</li>
                <li>TOTAL FINAL: <strong style="color: #007bff; font-size: 1.4em;"><?php echo number_format($total, 2, ',', '.'); ?> €</strong></li>
            </ul>
        </section>

        <p><a href="index.php">Volver al inicio</a></p>
    </main>
    
    <?php
} else {
    // Manejo de acceso directo (si no es POST)
    header("Location: folleto.php");
    exit();
}

require_once 'include/footer.php';
?>