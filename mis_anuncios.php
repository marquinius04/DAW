<?php
// [MODIFICADO]
// head.php ya incluye anuncios.php a través de sesion.php
$titulo_pagina = "Mis anuncios - PI";
require_once 'include/head.php'; 
controlar_acceso_privado(); //

// Creamos un array que contenga los dos anuncios ficticios como si fueran del usuario
$anuncios_del_usuario = [
    // El chalet (anuncio impar)
    array_merge(['id' => 1, 'pais' => 'España'], $anuncios_ficticios['impar']),
    // El estudio (anuncio par)
    array_merge(['id' => 2, 'pais' => 'España'], $anuncios_ficticios['par']),
    // Otro anuncio de prueba (anuncio impar)
    array_merge(['id' => 3, 'pais' => 'España'], $anuncios_ficticios['impar']),
];
?>

    <h2>Mis anuncios</h2>
    <p>A continuación se muestran los anuncios que has publicado:</p>

    <table style="width: 100%; border-collapse: collapse;">
      <thead>
        <tr>
          <th>Miniatura</th> <th>Título</th>
          <th>Precio</th> <th>Ubicación (Ciudad/País)</th> <th>Fecha de publicación</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($anuncios_del_usuario) > 0): ?>
            
            <?php foreach ($anuncios_del_usuario as $anuncio): 
                // Extraemos la primera foto como miniatura
                $foto_miniatura = $anuncio['fotos'][0] ?? 'img/default.jpg';
            ?>
                <tr>
                    <td style="text-align: center;">
                        <a href="aviso.php?id=<?php echo htmlspecialchars($anuncio['id']); ?>">
                            <img src="<?php echo htmlspecialchars($foto_miniatura); ?>" 
                                 alt="Miniatura" width="80" height="60" style="object-fit: cover;">
                        </a>
                    </td>
                    
                    <td>
                        <a href="aviso.php?id=<?php echo htmlspecialchars($anuncio['id']); ?>">
                            <?php echo htmlspecialchars($anuncio['titulo']); ?>
                        </a>
                    </td>
                    
                    <td style="color: darkgreen; font-weight: bold;">
                        <?php echo htmlspecialchars($anuncio['precio']); ?>
                    </td>
                    
                    <td>
                        <?php echo htmlspecialchars($anuncio['ciudad']); ?><br>
                        (<?php echo htmlspecialchars($anuncio['pais']); ?>)
                    </td>
                    
                    <td><?php echo htmlspecialchars($anuncio['fecha']); ?></td>
                </tr>
            <?php endforeach; ?>

        <?php else: ?>
            <tr>
                <td colspan="5">No tienes anuncios publicados actualmente.</td>
            </tr>
        <?php endif; ?>
      </tbody>
    </table>

<?php
require_once 'include/footer.php';
?>