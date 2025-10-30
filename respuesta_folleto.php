<?php
// Fichero: respuesta_folleto.php

// Define variables para la plantilla
$titulo_pagina = "Confirmación de solicitud de folleto - PI";
require_once 'include/head.php'; // Incluye el inicio del HTML, <head>, y <header>

// --- Lógica de Procesamiento y Cálculo (Requisito de la Práctica) ---

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Recolección de datos REALES
    $nombre = htmlspecialchars($_POST['nombre'] ?? 'N/A');
    $email = htmlspecialchars($_POST['email'] ?? 'N/A');
    $num_copias = (int)($_POST['num_copias'] ?? 1);
    $resolucion = (int)($_POST['resolucion'] ?? 150);
    $impresion_precio = htmlspecialchars($_POST['impresion_precio'] ?? 'con_precio'); 
    
    // ¡CAMBIO CLAVE! Recogemos el valor REAL del campo 'impresion_color'
    $impresion_color = htmlspecialchars($_POST['impresion_color'] ?? 'blanco_negro');

    // 2. Requisito: Valores FICTICIOS para Páginas y Fotos (para el resumen)
    $PAGINAS_FICTICIAS = 15; 
    $FOTOS_FICTICIAS = 45;   
    
    // 3. Cálculo del Coste Final
    $costeFijo = 2;
    $precioUnidad = 0;

    // Lógica para calcular precioUnidad (Basada en la tabla estática del formulario de folleto)
    
    // Se usa el valor real de $impresion_color para determinar el precio
    $es_color = ($impresion_color === "color"); 

    // Se asume 150 DPI para la tarifa baja (4 págs.) y >150 DPI para la tarifa alta (8 págs.)
    if ($resolucion === 150) {
        // Tarifa 4 págs.
        $precioUnidad = $es_color ? 7 : 5; 
    } else {
        // Tarifa 8 págs. (Resolución > 150 DPI)
        $precioUnidad = $es_color ? 12 : 8; 
    }
    
    $total = ($precioUnidad * $num_copias) + $costeFijo;

    ?>
    
    <main>
        <h2>✅ Solicitud enviada correctamente</h2>
        <p>Gracias por su solicitud. A continuación se muestra el **resumen del pedido y el coste final**:</p>

        <section id="resumen" style="border: 1px solid #0056b3; padding: 15px; background-color: #f0f7ff;">
            <h3>Resumen de la Solicitud Procesada</h3>
            <ul>
                <li>Nombre: <?php echo $nombre; ?></li>
                <li>Email: <?php echo $email; ?></li>
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
    // Manejo de acceso directo
    ?>
    <main>
        <h2>Error de Acceso</h2>
        <p>No hay datos de solicitud disponibles. Por favor, <a href="folleto.php">complete el formulario</a> nuevamente.</p>
    </main>
    <?php
}

require_once 'include/footer.php';
?>