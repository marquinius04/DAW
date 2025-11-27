<?php
require_once 'include/sesion.php';
require_once 'include/db_connect.php'; 
require_once 'include/flashdata.inc.php';

controlar_acceso_privado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = conectar_bd();

    // Recogida de datos
    $id_anuncio = (int)$_POST['anuncio']; // Ahora recibimos el ID
    $texto_adicional = trim($_POST['texto_adicional'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $calle = trim($_POST['calle'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $cp = trim($_POST['codigo_postal'] ?? '');
    $localidad = trim($_POST['localidad'] ?? '');
    $provincia = trim($_POST['provincia'] ?? '');
    $color_portada = trim($_POST['color_portada'] ?? '#000000');
    
    $num_copias = (int)($_POST['num_copias'] ?? 1);
    $resolucion = (int)($_POST['resolucion'] ?? 150);
    $es_color = ($_POST['impresion_color'] ?? 'blanco_negro') === 'color';
    $con_precio = isset($_POST['impresion_precio']); // Checkbox

    // --- Validación básica ---
    if ($id_anuncio <= 0 || empty($nombre) || empty($email) || empty($calle)) {
        set_flashdata('error', "Faltan datos obligatorios.");
        header("Location: folleto.php");
        exit();
    }
    
    // --- Cálculo de costes ---
    $costeFijo = 2;
    $precioUnidad = 0;
    
    if ($resolucion === 150) $precioUnidad = $es_color ? 7 : 5;
    else $precioUnidad = $es_color ? 12 : 8; // 300 DPI
    
    $coste_total = ($precioUnidad * $num_copias) + $costeFijo;

    // --- INSERTAR EN BD ---
    // Construimos la dirección completa en un string para el campo 'Direccion'
    $direccion_completa = "$calle, $numero, $cp, $localidad, $provincia";
    
    // Mapeo de booleanos a tinyint para la BD 
    $db_icolor = $es_color ? 1 : 0;
    $db_iprecio = $con_precio ? 1 : 0;
    $fecha_actual = date('Y-m-d'); // Para campo Fecha 

    $sql = "INSERT INTO solicitudes (Anuncio, Texto, Nombre, Email, Direccion, Telefono, Color, Copias, Resolucion, Fecha, IColor, IPrecio, Coste) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("issssssiisiid", 
        $id_anuncio, 
        $texto_adicional, 
        $nombre, 
        $email, 
        $direccion_completa, 
        $telefono, 
        $color_portada, 
        $num_copias, 
        $resolucion, 
        $fecha_actual, 
        $db_icolor, 
        $db_iprecio, 
        $coste_total
    );

    if ($stmt->execute()) {
        $id_solicitud = $mysqli->insert_id;
        // Obtener título del anuncio para mostrar
        $res_ad = $mysqli->query("SELECT Titulo FROM anuncios WHERE IdAnuncio = $id_anuncio");
        $titulo_anuncio = $res_ad->fetch_assoc()['Titulo'];
    } else {
        set_flashdata('error', "Error al guardar solicitud: " . $stmt->error);
        header("Location: folleto.php");
        exit();
    }
    $stmt->close();
    $mysqli->close();

    // --- Mostrar Confirmación ---
    $titulo_pagina = "Solicitud Confirmada - PI";
    require_once 'include/head.php'; 
    ?>
    
    <main>
        <h2><span class="icono">print</span> Solicitud registrada (ID: <?= $id_solicitud ?>)</h2>
        
        <div class="caja-lateral" style="background-color: #e8f5e9; border: 1px solid #4caf50;">
            <h3>Detalle del Pedido</h3>
            <p><strong>Anuncio:</strong> <?= htmlspecialchars($titulo_anuncio) ?></p>
            <p><strong>Coste Total:</strong> <span style="font-size: 1.5em; font-weight: bold;"><?= number_format($coste_total, 2) ?> €</span></p>
            <hr>
            <p>Se enviarán <strong><?= $num_copias ?></strong> copias a:</p>
            <p><?= htmlspecialchars($direccion_completa) ?></p>
        </div>

        <a href="index.php" class="btn-contacto" style="display:inline-block; width:auto; margin-top:20px;">Volver al inicio</a>
    </main>

    <?php
    require_once 'include/footer.php';
    exit();

} else {
    header("Location: folleto.php");
    exit();
}
?>