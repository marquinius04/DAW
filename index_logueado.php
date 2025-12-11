<?php

$titulo_pagina = "PI - Menú principal (Logueado)";
$menu_tipo = 'privado';

require_once 'include/head.php'; 
require_once 'include/db_connect.php'; 

// Controla que el usuario esté logueado para ver esta página
controlar_acceso_privado();

$mysqli = conectar_bd();

// Lógica de los últimos anuncios, anuncio escogido 

$sql_anuncios = "
    SELECT 
        A.IdAnuncio, A.FPrincipal, A.Titulo, A.Precio 
    FROM 
        anuncios A
    ORDER BY 
        A.FRegistro DESC 
    LIMIT 5
";
$stmt = $mysqli->prepare($sql_anuncios);

if ($stmt === false) {
    $error_anuncios = "Error al preparar la consulta: " . $mysqli->error;
    $resultado_anuncios = null;
} else {
    $stmt->execute();
    $resultado_anuncios = $stmt->get_result();
    $error_anuncios = null;
}


// Lógica del anuncio del día

$fichero_seleccionados = __DIR__ . '/data/anuncios_seleccionados.txt';
$anuncio_destacado = null;
$experto_nombre = "";
$experto_comentario = "";

if (file_exists($fichero_seleccionados)) {
    $lineas = file($fichero_seleccionados, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    if ($lineas !== false && count($lineas) > 0) {
        $intentos = 0;
        $max_intentos = 3;
        do {
            $indice_aleatorio = array_rand($lineas);
            $linea = $lineas[$indice_aleatorio];
            $datos = explode('|', $linea);
            
            if (count($datos) >= 3) {
                $id_candidato = (int)$datos[0];
                $experto_nombre = htmlspecialchars($datos[1]);
                $experto_comentario = htmlspecialchars($datos[2]);
                
                // Consultamos datos mínimos para mostrarlo
                $sql_destacado = "SELECT A.IdAnuncio, A.Titulo, A.FPrincipal, A.Precio, A.Ciudad 
                                  FROM anuncios A 
                                  WHERE A.IdAnuncio = $id_candidato";
                $res_dest = $mysqli->query($sql_destacado);
                
                if ($res_dest && $res_dest->num_rows > 0) {
                    $anuncio_destacado = $res_dest->fetch_assoc();
                }
            }
            $intentos++;
            // Evitar bucle infinito si el anuncio del txt ya no existe en BD
            if (!$anuncio_destacado) {
                unset($lineas[$indice_aleatorio]);
                $lineas = array_values($lineas);
            }
            
        } while ($anuncio_destacado === null && count($lineas) > 0 && $intentos < $max_intentos);
    }
}


// Lógica del consejo del día

$fichero_consejos = __DIR__ . '/data/consejos.json';
$consejo_del_dia = null;

if (file_exists($fichero_consejos)) {
    $json_content = file_get_contents($fichero_consejos);
    $consejos = json_decode($json_content, true);
    
    if ($consejos && count($consejos) > 0) {
         $consejo_del_dia = $consejos[array_rand($consejos)];
    }
}
?>

    <section style="background-color: #e5efff; border: 1px solid var(--color-primario);">
        
        <h2><?php echo get_saludo(); ?></h2> 
        
        <?php if (isset($_SESSION['ultima_visita'])): ?>
            <p>Tu última visita fue el <?php echo htmlspecialchars($_SESSION['ultima_visita']); ?>.</p>
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

    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 20px;">
        
        <?php if ($anuncio_destacado): ?>
        <section style="flex: 2; min-width: 300px; border: 2px solid #ff8c00; background-color: #fff8e1; margin-bottom: 0;">
            <h2><span class="icono">stars</span> Anuncio del día</h2>
            <div style="display: flex; gap: 15px;">
                <img src="<?= htmlspecialchars($anuncio_destacado['FPrincipal'] ?? 'img/default.jpg') ?>" 
                     alt="Foto destacada" 
                     style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px;">
                
                <div>
                    <h3 style="margin-top: 0; font-size: 1.1em;"><?= htmlspecialchars($anuncio_destacado['Titulo']) ?></h3>
                    <p style="margin: 5px 0;"><strong><?= number_format($anuncio_destacado['Precio'], 0, ',', '.') ?> €</strong></p>
                    <p style="font-size: 0.9em;"><?= htmlspecialchars($anuncio_destacado['Ciudad']) ?></p>
                    
                    <div style="background: white; padding: 8px; border-radius: 4px; margin-top: 8px; font-size: 0.9em; font-style: italic;">
                        "<?= $experto_comentario ?>"
                        <br><span style="color: #666; font-size: 0.85em;">— <?= $experto_nombre ?></span>
                    </div>
                    <br>
                    <a href="aviso.php?id=<?= $anuncio_destacado['IdAnuncio'] ?>" style="font-weight: bold;">Ver anuncio &rarr;</a>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($consejo_del_dia): ?>
        <section style="flex: 1; min-width: 250px; border: 2px solid #4caf50; background-color: #e8f5e9; margin-bottom: 0;">
            <h2><span class="icono" style="color: #2e7d32;">lightbulb</span> Consejo del día</h2>
            
            <p><strong>Categoría:</strong> <?= htmlspecialchars($consejo_del_dia['categoria']) ?></p>
            
            <?php 
                $color_imp = 'black';
                if ($consejo_del_dia['importancia'] == 'Alta') $color_imp = 'red';
                elseif ($consejo_del_dia['importancia'] == 'Media') $color_imp = 'orange';
                else $color_imp = 'green';
            ?>
            <p><strong>Importancia:</strong> <span style="color: <?= $color_imp ?>; font-weight: bold;"><?= htmlspecialchars($consejo_del_dia['importancia']) ?></span></p>
            <hr style="border-top: 1px solid #c8e6c9;">
            <p style="font-size: 1.1em; font-style: italic; color: #333;">
                "<?= htmlspecialchars($consejo_del_dia['descripcion']) ?>"
            </p>
        </section>
        <?php endif; ?>
        
    </div>
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
if (isset($stmt)) $stmt->close();
$mysqli->close(); 
require_once 'include/footer.php';
?>