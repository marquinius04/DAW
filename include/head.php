<?php
// 1. Incluir el gestor de sesión ANTES de cualquier HTML.
// Esto inicia la sesión, gestiona flashdata y el auto-login por cookie.

// Usamos __DIR__ para que siempre busque 'sesion.php' en su misma carpeta 
require_once __DIR__ . '/sesion.php';

// Variables por defecto
$titulo_pagina = $titulo_pagina ?? "PI - Pisos & Inmuebles"; 
$body_id = $body_id ?? "";
$menu_tipo = $menu_tipo ?? 'privado';

// 2. Lógica de estilos CSS 
// Carga el estilo guardado en la sesión, o el principal si no hay sesión.
$estilo_seleccionado = $_SESSION['estilo_css'] ?? 'css/styles.css';
$estilo_principal = 'css/styles.css';

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?php echo $titulo_pagina; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <?php
    // 3. Carga de los estilos
    // Si el estilo de sesión NO es el principal, lo cargamos como "principal"
    // y el de "styles.css" como alternativo.
    if ($estilo_seleccionado !== $estilo_principal) {
        echo '<link rel="stylesheet" type="text/css" href="' . htmlspecialchars($estilo_seleccionado) . '" title="Estilo principal">' . "\n";
        echo '  <link rel="alternate stylesheet" type="text/css" href="' . htmlspecialchars($estilo_principal) . '" title="Estilo por defecto">' . "\n";
    } else {
        // Si es el principal, lo cargamos normal
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
            <?php if ($menu_tipo === 'publico'): ?>
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
                    <li><a href="crear_anuncio.php"><span class="icono">add_circle</span>Crear anuncio</a></li>
                    <li><a href="mis_mensajes.php"><span class="icono">mail</span>Mis mensajes</a></li>
                    <li><a href="folleto.php"><span class="icono">description</span>Solicitar folleto</a></li>
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
// Mostramos el mensaje de error "flash" si existe
if (isset($flash_error) && $flash_error !== null): 
?>
    <p style='color: red; border: 1px solid red; padding: 10px; background-color: #ffeaea; margin-top: 15px;'>
        ⛔ Error: <?php echo $flash_error; ?>
    </p>
<?php endif; ?>