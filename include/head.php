<?php
$titulo_pagina = $titulo_pagina ?? "PI - Pisos & Inmuebles"; 
$body_id = $body_id ?? "";
$menu_tipo = $menu_tipo ?? 'privado';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?php echo $titulo_pagina; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link rel="stylesheet" type="text/css" href="css/styles.css" title="Estilo principal">

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
                <li><a href="index.php"><span class="icono">logout</span>Salir</a></li>
            </ul>
            <?php endif; ?>
        </nav>
    </header>
  
<main id="main-content">