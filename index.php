<?php
require_once 'include/sesion.php'; 
require_once 'include/db_connect.php'; 
require_once 'include/flashdata.inc.php'; 

$mensaje_error = get_flashdata('error');
$titulo_pagina = "Acceso y anuncios Recientes";
$body_id = "loginPage"; 
$menu_tipo = 'publico';

controlar_acceso_publico();

$mysqli = conectar_bd();

// Consulta para obtener los últimos 5 anuncios 
$sql_anuncios = "
    SELECT 
        A.IdAnuncio, A.FPrincipal, A.Titulo, A.FRegistro, A.Ciudad, A.Precio, P.NomPais 
    FROM 
        anuncios A
    JOIN 
        paises P ON A.Pais = P.IdPais
    ORDER BY 
        A.FRegistro DESC 
    LIMIT 5
";

$resultado_anuncios = $mysqli->query($sql_anuncios);
?>

<?php require_once 'include/head.php'; ?>

<?php if ($mensaje_error): ?>
    <p style="color: red; padding: 10px; border: 1px solid red; background-color: #ffeaea; margin-bottom: 20px;">
        ⚠️ <?= htmlspecialchars($mensaje_error) ?>
    </p>
<?php endif; ?>

    <section>
      <h2>Acceso de usuario</h2>
      <form action="respuesta_login.php" method="post">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>
        
        <label for="clave">Contraseña:</label>
        <input type="password" id="clave" name="clave" required>
        
        <div style="margin-top: 10px;">
            <input type="checkbox" id="recordarme" name="recordarme">
            <label for="recordarme" style="display:inline;">Recordarme en este equipo</label>
        </div>
        
        <button type="submit" style="margin-top:15px;">Entrar</button>
      </form>
    </section>

    <section>
      <h2>Búsqueda rápida</h2>
      <form action="resultados.php" method="get">
        <label for="q">Término de búsqueda:</label>
        <input type="text" id="q" name="q" placeholder="Ej: piso alquiler alicante">
        <button type="submit">Buscar</button>
      </form>
    </section>

    <section>
      <h2>Últimos anuncios publicados</h2>
      <?php if ($resultado_anuncios && $resultado_anuncios->num_rows > 0): ?>
          <ul class="listado-anuncios">
              <?php while ($anuncio = $resultado_anuncios->fetch_assoc()): ?>
                  <li>
                      <article>
                          <a href="aviso.php?id=<?= $anuncio['IdAnuncio'] ?>"> <img src="<?= htmlspecialchars($anuncio['FPrincipal'] ?? 'img/default.jpg') ?>" alt="Foto anuncio" width="100">
                              <h3><?= htmlspecialchars($anuncio['Titulo']) ?></h3>
                              <p class="precio"><?= number_format($anuncio['Precio'], 2, ',', '.') ?> €</p>
                              <p class="fecha"><small>📅 <?= date('d/m/Y', strtotime($anuncio['FRegistro'])) ?></small></p>
                              <p class="ubicacion"><?= htmlspecialchars($anuncio['Ciudad']) ?>, <?= htmlspecialchars($anuncio['NomPais']) ?></p>
                          </a>
                      </article>
                  </li>
              <?php endwhile; ?>
          </ul>
      <?php else: ?>
          <p>No hay anuncios publicados actualmente.</p>
      <?php endif; ?>
    </section>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>