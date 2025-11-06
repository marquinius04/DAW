<?php
// [MODIFICADO]
// 1. Incluir el gestor de sesión
require_once 'include/sesion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // [MODIFICADO]
    // 2. Controlar acceso
    controlar_acceso_privado();
    
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $calle = trim($_POST['calle'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $codigo_postal = trim($_POST['codigo_postal'] ?? '');
    $localidad = trim($_POST['localidad'] ?? '');
    $provincia = trim($_POST['provincia'] ?? '');
    $anuncio = trim($_POST['anuncio'] ?? '');
    
    // Recoger el resto de datos
    $num_copias = (int)($_POST['num_copias'] ?? 1);
    $resolucion = (int)($_POST['resolucion'] ?? 150);
    $impresion_precio = htmlspecialchars($_POST['impresion_precio'] ?? 'con_precio'); 
    $impresion_color = htmlspecialchars($_POST['impresion_color'] ?? 'blanco_negro');
    
    $error_mensaje = "";

    // Validación de campos obligatorios (*)
    if (empty($nombre)) $error_mensaje = "El nombre y apellidos son obligatorios.";
    elseif (empty($email)) $error_mensaje = "El correo electrónico es obligatorio.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error_mensaje = "El formato del correo electrónico no es válido.";
    elseif (empty($calle)) $error_mensaje = "La calle es obligatoria.";
    elseif (empty($numero)) $error_mensaje = "El número de la dirección es obligatorio.";
    elseif (empty($codigo_postal)) $error_mensaje = "El código postal es obligatorio.";
    elseif (empty($localidad)) $error_mensaje = "La localidad es obligatoria.";
    elseif (empty($provincia)) $error_mensaje = "La provincia es obligatoria.";
    elseif (empty($anuncio)) $error_mensaje = "Debe seleccionar un anuncio para imprimir.";
    
    // Redirección si hay error
    if ($error_mensaje !== "") {
        // [MODIFICADO] Usamos flashdata 
        $_SESSION['flash_error'] = $error_mensaje;
        $datos_previos = http_build_query($_POST);
        header("Location: folleto.php?{$datos_previos}");
        exit();
    }
    
    $titulo_pagina = "Confirmación de solicitud de folleto - PI";
    require_once 'include/head.php'; 
    
    // Datos ficticios
    $PAGINAS_FICTICIAS = 15; 
    $FOTOS_FICTICIAS = 45;   
    
    // Cálculo del coste final
    $costeFijo = 2;
    $precioUnidad = 0;
    $es_color = ($impresion_color === "color"); 
    if ($resolucion === 150) $precioUnidad = $es_color ? 7 : 5;
    else $precioUnidad = $es_color ? 12 : 8;
    $total = ($precioUnidad * $num_copias) + $costeFijo;

    ?>
    
    <main>
        <h2><span class="icono">check_circle</span> Solicitud enviada correctamente</h2>
        <p>Gracias por su solicitud. A continuación se muestra el resumen del pedido y el coste final:</p>

        <section id="resumen" class="caja-lateral" style="background-color: #f0f7ff; border: 1px solid var(--color-primario); line-height: 1.8;">
            
            <h3>Resumen de la solicitud procesada</h3>
            
            <p style="margin-bottom: 0.75em;">Nombre: <strong><?php echo htmlspecialchars($nombre); ?></strong></p>
            <p style="margin-bottom: 0.75em;">Email: <strong><?php echo htmlspecialchars($email); ?></strong></p>
            <p style="margin-bottom: 0.75em;">Dirección: <strong><?php echo htmlspecialchars("$calle, $numero, $codigo_postal, $localidad, $provincia"); ?></strong></p>
            <p style="margin-bottom: 0.75em;">Anuncio: <strong><?php echo htmlspecialchars($anuncio); ?></strong></p>
            <p style="margin-bottom: 0.75em;">Copias Solicitadas: <strong><?php echo $num_copias; ?></strong></p>
            <p style="margin-bottom: 0.75em;">Resolución de Fotos: <strong><?php echo $resolucion; ?> DPI</strong></p>
            <p style="margin-bottom: 0.75em;">Tipo de Impresión: <strong><?php echo $es_color ? "Color" : "Blanco y Negro"; ?></strong></p>
            <p style="margin-bottom: 0.75em;">Impresión de Precio: <strong><?php echo $impresion_precio === "con_precio" ? "Sí" : "No"; ?></strong></p>
            
            <p style="margin-bottom: 0.75em;">Páginas (EJEMPLO): <strong><?php echo $PAGINAS_FICTICIAS; ?></strong></p>
            <p style="margin-bottom: 0.75em;">Fotos (EJEMPLO): <strong><?php echo $FOTOS_FICTICIAS; ?></strong></p>
            <p style="margin-bottom: 0.75em;">Precio Unitario (calculado): <strong><?php echo $precioUnidad; ?> €</strong></p>
            <p style="margin-bottom: 1.5em;">Coste Fijo de Envío: <strong><?php echo $costeFijo; ?> €</strong></p>
            
            <p style="font-size: 1.4em; font-weight: bold; color: var(--color-primario); text-align: center; margin-top: 1em;">
                TOTAL FINAL: <?php echo number_format($total, 2, ',', '.'); ?> €
            </p>
        </section>

        <a href="index.php" style="display: inline-block; background-color: var(--color-primario); color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-top: 20px; font-weight: bold;">
            <span class="icono">home</span> Volver al inicio
        </a>
    </main>
    
    <?php
} else {
    header("Location: folleto.php");
    exit();
}

require_once 'include/footer.php';
?>