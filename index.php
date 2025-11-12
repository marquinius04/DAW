<?php
// /index.php

// -------------------------------------------------------------
// 1. GESTIÓN DE SESIÓN, CONEXIÓN Y ERRORES (¡SIEMPRE PRIMERO!)
// -------------------------------------------------------------
require_once 'include/sesion.php'; 
require_once 'include/db_connect.php'; 
require_once 'include/flashdata.inc.php'; 

// Recupera el mensaje de error de login (si existe) y lo borra de la sesión
$mensaje_error = get_flashdata('error');

// Configuración de la página
$titulo_pagina = "Acceso y Anuncios Recientes";
$body_id = "loginPage"; 
$menu_tipo = 'publico';

// Si el usuario ya está logueado, se le redirige a la zona privada
controlar_acceso_publico();

// -------------------------------------------------------------
// 2. LÓGICA DE ÚLTIMOS ANUNCIOS (CONEXIÓN Y CONSULTA)
// -------------------------------------------------------------
$mysqli = conectar_bd();

// Consulta para obtener los últimos 5 anuncios
$sql_anuncios = "
    SELECT 
        A.IdAnuncio, A.FPrincipal, A.Titulo, A.FRegistro, A.Ciudad, A.Precio, P.NomPais 
    FROM 
        ANUNCIOS A
    JOIN 
        PAISES P ON A.Pais = P.IdPais
    ORDER BY 
        A.FRegistro DESC 
    LIMIT 5
";

if (!$resultado_anuncios = $mysqli->query($sql_anuncios)) {
    $error_anuncios = "Error al cargar los últimos anuncios: " . $mysqli->error;
} else {
    $error_anuncios = null;
}
?>

<?php require_once 'include/head.php'; // Incluye el inicio del HTML ?>

<?php if ($mensaje_error): ?>
    <p style="color: red; padding: 10px; border: 1px solid red; background-color: #ffeaea; margin-bottom: 20px;">
        ⚠️ **Error de Acceso:** <?= htmlspecialchars($mensaje_error) ?>
    </p>
<?php endif; ?>

<?php 
// --- Lógica de "recordarme" ---
if (isset($_COOKIE['recordar_usuario']) && isset($_COOKIE['ultima_visita_real'])): 
    $usuario_recordado = htmlspecialchars($_COOKIE['recordar_usuario']);
    $ultima_visita = htmlspecialchars($_COOKIE['ultima_visita_real']);
?>
    <section style="background-color: #e5efff; border: 1px solid var(--color-primario);">
        <h2>Bienvenido de nuevo, <?php echo $usuario_recordado; ?></h2>
        <p>Su última visita fue el <?php echo $ultima_visita; ?>.</p>
        <p>Pulse 'Entrar' para acceder directamente o <a href="logout.php">pulse aquí si no es usted</a>.</p>
        
        <form action="respuesta_login.php" method="post" style="padding: 0; box-shadow: none;">
            <input type="hidden" name="usuario" value="<?php echo $usuario_recordado; ?>">
            <input type="hidden" name="clave" value="<?php echo htmlspecialchars($_COOKIE['recordar_clave']); ?>">
            <input type="hidden" name="recordarme" value="on"> <button type="submit">Entrar</button>
        </form>
    </section>

<?php else: ?>

    <section>
      <h2>Acceso de usuario</h2>
      <form action="respuesta_login.php" method="post">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" >
        <span id="usuarioError" class="error"></span>
        <label for="clave">Contraseña:</label>
        <input type="password" id="clave" name="clave" >
        <span id="claveError" class="error"></span> 
        
        <div>
            <input type="checkbox" id="recordarme" name="recordarme">
            <label for="recordarme" style="font-weight: normal; color: var(--color-texto);">Recordarme en este equipo</label>
        </div>
        
        <button type="submit">Entrar</button>
      </form>
    </section>

<?php endif; ?>

    <section>
      <h2>Búsqueda rápida</h2>
      <form action="resultados.php" method="get">
        <label for="q">Término de búsqueda:</label>
        <input type="text" id="q" name="q" placeholder="Ej: piso alquiler alicante">
        <button type="submit">Buscar</button>
      </form>
    </section>

    <section>
      <h2>Últimos 5 anuncios</h2>
      <?php if ($error_anuncios): ?>
          <p style="color: red;"><?= $error_anuncios ?></p>
      <?php elseif ($resultado_anuncios->num_rows > 0): ?>
          <ul>
              <?php while ($anuncio = $resultado_anuncios->fetch_assoc()): ?>
                  <li>
                      <article>
                          <a href="detalle_anuncio.php?id=<?= $anuncio['IdAnuncio'] ?>">
                              <img src="<?= htmlspecialchars($anuncio['FPrincipal'] ?? 'img/default.jpg') ?>" alt="Foto de <?= htmlspecialchars($anuncio['Titulo']) ?>" width="100">
                              <h3><?= htmlspecialchars($anuncio['Titulo']) ?></h3>
                              <p><?= number_format($anuncio['Precio'], 2, ',', '.') ?> €</p>
                              <p>Ubicación: <?= htmlspecialchars($anuncio['Ciudad']) ?>, <?= htmlspecialchars($anuncio['NomPais']) ?></p>
                          </a>
                      </article>
                  </li>
              <?php endwhile; ?>
          </ul>
          <?php $resultado_anuncios->close(); ?>
      <?php else: ?>
          <p>No hay anuncios publicados actualmente.</p>
      <?php endif; ?>
    </section>

<?php
// Cierre de la conexión a la BD
$mysqli->close();
require_once 'include/footer.php';
?>