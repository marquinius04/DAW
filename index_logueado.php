<?php
$titulo_pagina = "PI - Menú Principal (Logueado)";
$menu_tipo = 'privado';

require_once 'include/head.php'; 
require_once 'include/db_connect.php'; 

controlar_acceso_privado();

// LÓGICA DE ÚLTIMOS ANUNCIOS 
$mysqli = conectar_bd();
$sql_anuncios = "
    SELECT 
        A.IdAnuncio, A.FPrincipal, A.Titulo, A.Precio 
    FROM 
        ANUNCIOS A
    ORDER BY 
        A.FRegistro DESC 
    LIMIT 5
"; // Obtenemos los 5 más nuevos

if (!$resultado_anuncios = $mysqli->query($sql_anuncios)) {
    // Manejo de error si la consulta falla
    $error_anuncios = "Error al cargar los últimos anuncios: " . $mysqli->error;
} else {
    $error_anuncios = null;
}
?>

    <section style="background-color: #e5efff; border: 1px solid var(--color-primario);">
        
        <h2><?php echo get_saludo(); ?></h2> 
        
        <?php 
        if (isset($_SESSION['ultima_visita'])): 
        ?>
            <p>Tu última visita (registrada por la cookie) fue el <?php echo htmlspecialchars($_SESSION['ultima_visita']); ?>.</p>
        <?php else: ?>
            <p>Te has conectado ahora mismo.</p>
        <?php endif; ?>
        
    </section>

    <section>
      <h2>Búsqueda rápida</h2>
      <form action="resultados.php" method="get"> 
        <label for="q">Búsqueda rápida:</label>
        <input type="text" id="q" name="q" placeholder="Ej: local alquiler alicante">
        <button type="submit">Buscar</button>
      </form>
    </section>

    <section>
      <h2>Últimos anuncios</h2>
      
      <?php if ($error_anuncios): ?>
          <p style="color: red;"><?= $error_anuncios ?></p>
          
      <?php elseif ($resultado_anuncios->num_rows > 0): ?>
          <ul>
              <?php while ($anuncio = $resultado_anuncios->fetch_assoc()): ?>
                  <li>
                      <article>
                          <a href="aviso.php?id=<?= $anuncio['IdAnuncio'] ?>"> 
                              <img src="<?= htmlspecialchars($anuncio['FPrincipal'] ?? 'img/default.jpg') ?>" alt="Foto de <?= htmlspecialchars($anuncio['Titulo']) ?>" width="100">
                              <h3><?= htmlspecialchars($anuncio['Titulo']) ?></h3>
                              <p><?= number_format($anuncio['Precio'], 2, ',', '.') ?> €</p>
                          </a>
                      </article>
                  </li>
              <?php endwhile; ?>
          </ul>
          <?php $resultado_anuncios->close(); // Muestra resultados ?>
      <?php else: ?>
          <p>No hay anuncios publicados actualmente.</p>
      <?php endif; ?>
      
    </section>

<?php
$mysqli->close(); 
require_once 'include/footer.php';
?>