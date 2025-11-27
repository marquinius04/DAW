<?php
// Incluye la lógica de sesión antes de que cargue el html
require_once __DIR__ . '/sesion.php';

// --- CRUCIAL: Incluimos la conexión a BD ---
require_once __DIR__ . '/db_connect.php'; 
// ------------------------------------------

// --- Variables de configuración de la página ---
$titulo_pagina = $titulo_pagina ?? "PI - Pisos & Inmuebles"; 
$body_id = $body_id ?? "";
$menu_tipo = $menu_tipo ?? 'privado';

// --- Lógica de estilos CSS ---
$estilo_id_sesion = $_SESSION['estilo'] ?? 0;
$estilo_principal = 'css/styles.css'; 
$estilo_seleccionado_url = $estilo_principal;

if ($estilo_id_sesion > 0) {
    $mysqli = conectar_bd();
    $sql_estilo = "SELECT Fichero FROM estilos WHERE IdEstilo = ?";
    $stmt = $mysqli->prepare($sql_estilo);
    if ($stmt) {
        $stmt->bind_param("i", $estilo_id_sesion);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($fila = $resultado->fetch_assoc()) {
            $estilo_seleccionado_url = htmlspecialchars($fila['Fichero']);
        }
        $stmt->close();
    }
    $mysqli->close();
}

$estilo_seleccionado = $estilo_seleccionado_url;

// --- Flash Data ---
require_once 'include/flashdata.inc.php'; 
$flash_error = get_flashdata('error');

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?php echo $titulo_pagina; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <?php
    if ($estilo_seleccionado !== $estilo_principal) {
        echo '<link rel="stylesheet" type="text/css" href="' . htmlspecialchars($estilo_seleccionado) . '" title="Estilo principal">' . "\n";
        echo '  <link rel="alternate stylesheet" type="text/css" href="' . htmlspecialchars($estilo_principal) . '" title="Estilo por defecto">' . "\n";
    } else {
        echo '<link rel="stylesheet" type="text/css" href="' . htmlspecialchars($estilo_principal) . '" title="Estilo principal">' . "\n";
    }
  ?>
  
  <link rel="alternate stylesheet" type="text/css" href="css/night.css" title="Modo noche">
  <link rel="alternate stylesheet" type="text/css" href="css/contrast.css" title="Alto contraste">
  <link rel="alternate stylesheet" type="text/css" href="css/big.css" title="Texto grande">
  <link rel="alternate stylesheet" type="text/css" href="css/contrast_big.css" title="Contraste + Texto grande">

  <link rel="stylesheet" type="text/css" href="css/print.css" media="print">
</head>
<body id="<?php echo $body_id; ?>"> 
    <a href="#main-content" class="skip-link">Saltar al contenido principal</a>
    <header>
        <h1>PI - Pisos & Inmuebles</h1>
        <nav>
            <?php 
            if ($menu_tipo === 'publico'): 
            ?>
            <ul>
                <li><a href="index.php"><span class="icono">home</span>Inicio</a></li>
                <li><a href="registro.php"><span class="icono">person_add</span>Registro</a></li>
                <li><a href="busqueda.php"><span class="icono">search</span>Búsqueda avanzada</a></li>
            </ul>
            <?php else: ?>
            <ul class="menu">
                <li><a href="index_logueado.php"><span class="icono">home</span>Inicio</a></li>
                <li class="submenu">
                    <a href="perfil.php"><span class="icono">person</span>Perfil</a>
                    <ul>
                        <li><a href="mis_anuncios.php"><span class="icono">article</span>Mis anuncios</a></li>
                        <li><a href="crear_anuncio.php"><span class="icono">add_circle</span>Crear anuncio</a></li>
                        <li><a href="modificar_datos.php"><span class="icono">manage_accounts</span>Mis datos</a></li>
                        <li><a href="mis_mensajes.php"><span class="icono">mail</span>Mis mensajes</a></li>
                        <li><a href="folleto.php"><span class="icono">description</span>Solicitar folleto</a></li>
                        <li><a href="configurar.php"><span class="icono">settings</span>Configuración</a></li>
                    </ul>
                </li>
                <li><a href="busqueda.php"><span class="icono">search</span>Búsqueda avanzada</a></li>
                <li><a href="logout.php"><span class="icono">logout</span>Salir</a></li>
            </ul>
            <?php endif; ?>
        </nav>
    </header>
  
<main id="main-content">

<?php 
if (isset($flash_error) && $flash_error !== null): 
?>
    <p style='color: red; border: 1px solid red; padding: 10px; background-color: #ffeaea; margin-top: 15px;'>
        ⛔ Error: <?php echo $flash_error; ?>
    </p>
<?php endif; ?>