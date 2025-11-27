<?php

$titulo_pagina = "PI - Menú principal (Logueado)";
$menu_tipo = 'privado';

require_once 'include/head.php'; 
require_once 'include/db_connect.php'; 

// Controla que el usuario esté logueado para ver esta página
controlar_acceso_privado();

// -----------------------------------------------------------------
// LÓGICA DE ÚLTIMOS ANUNCIOS
// -----------------------------------------------------------------
$mysqli = conectar_bd();

// Consulta estática 
$sql_anuncios = "
    SELECT 
        A.IdAnuncio, A.FPrincipal, A.Titulo, A.Precio 
    FROM 
        anuncios A
    ORDER BY 
        A.FRegistro DESC 
    LIMIT 5
"; // Obtenemos los 5 más nuevos

// PREPARAR la sentencia
$stmt = $mysqli->prepare($sql_anuncios);

if ($stmt === false) {
    // Manejo de error si la consulta falla
    $error_anuncios = "Error al preparar la consulta de últimos anuncios: " . $mysqli->error;
    $resultado_anuncios = null;
} else {
    // EJECUTAR la sentencia
    $stmt->execute();
    // OBTENER el resultado
    $resultado_anuncios = $stmt->get_result();
    $error_anuncios = null;
}
?>

    <section style="background-color: #e5efff; border: 1px solid var(--color-primario);">
        
        <h2><?php echo get_saludo(); ?></h2> 
        
        <?php 
        // Mensaje de última visita 
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
          <p style="color: red;"><?= htmlspecialchars($error_anuncios) ?></p>
          
      <?php elseif ($resultado_anuncios && $resultado_anuncios->num_rows > 0): ?>
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
      <?php else: ?>
          <p>No hay anuncios publicados actualmente.</p>
      <?php endif; ?>
      
    </section>

<?php
// CIERRE DE RECURSOS Y FOOTER
if (isset($stmt)) $stmt->close();
$mysqli->close(); // Cierra la conexión a la BD
require_once 'include/footer.php';
?>