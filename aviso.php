<?php
// 1. Incluimos head.php al principio
// Esto carga 'sesion.php', que a su vez carga 'data/anuncios.php'.
// Ahora la variable $anuncios_ficticios existirá para el resto del script.
require_once 'include/head.php'; 

// 2. Obtener ID del anuncio
$anuncio_id = (int)($_GET['id'] ?? 0); 
$anuncio = null;

if ($anuncio_id > 0) {
    // Lógica par/impar para seleccionar datos
    $clave_anuncio = ($anuncio_id % 2 === 0) ? 'par' : 'impar';
    // $anuncios_ficticios AHORA SÍ existe
    $anuncio = $anuncios_ficticios[$clave_anuncio] ?? null;
}

// 3. Definir título de página
if ($anuncio) {
    $titulo_pagina = $anuncio['titulo'] . " - PI";
} else {
    $titulo_pagina = "Anuncio no encontrado - PI";
}

// Esta página ahora es PRIVADA. La función viene de 'sesion.php'.
controlar_acceso_privado();

// Si el anuncio es válido, lo añadimos a la sección de "visitados"
if ($anuncio) {
    // La función add_anuncio_visitado() está definida en sesion.php
    add_anuncio_visitado($anuncio_id, $anuncio);
}
?>

<?php if ($anuncio): ?>
    
    <div class="grid-container detalle-anuncio">
        
        <div class="columna-principal">
            
            <h2><?php echo htmlspecialchars($anuncio['titulo']); ?></h2>
            <p style="margin-bottom: 20px;">Publicado el: <?php echo htmlspecialchars($anuncio['fecha']); ?></p>

            <img class="imagen-principal" src="<?php echo htmlspecialchars($anuncio['fotos'][0] ?? 'img/default.jpg'); ?>" alt="Foto principal del inmueble">
            
            <h3>Descripción</h3>
            <p><?php echo nl2br(htmlspecialchars($anuncio['texto'])); ?></p>
            
            <h3>Características</h3>
            <div class="anuncio-detalles">
                <p><strong>Precio:</strong> <?php echo htmlspecialchars($anuncio['precio']); ?></p>
                <p><strong>Ciudad:</strong> <?php echo htmlspecialchars($anuncio['ciudad']); ?></p>
                <p><strong>Fecha:</strong> <?php echo htmlspecialchars($anuncio['fecha']); ?></p>
                
                <?php foreach ($anuncio['caracteristicas'] as $key => $value): ?>
                    <p><strong><?php echo htmlspecialchars($key); ?>:</strong> <?php echo htmlspecialchars($value); ?></p>
                <?php endforeach; ?>

                </div>
        </div>

        <div class="columna-lateral">
            <section class="caja-lateral">
                <h3>Más fotos</h3>
                <div class="galeria-extra">
                    <?php 
                    // Mostrar fotos secundarias si existen
                    $fotos_secundarias = array_slice($anuncio['fotos'], 1);
                    foreach ($fotos_secundarias as $foto_url): 
                    ?>
                        <img src="<?php echo htmlspecialchars($foto_url); ?>" alt="Vista extra del inmueble">
                    <?php endforeach; ?>
                </div>
            </section>
            
            <a href="mensaje.php?anuncio_id=<?php echo $anuncio_id; ?>" class="btn-contacto" style="margin-bottom: 10px;"><span class="icono">mail</span>Enviar mensaje al anunciante</a>
            <a href="anyadir_foto.php?anuncio_id=<?php echo $anuncio_id; ?>" class="btn-contacto">➕ Añadir foto a anuncio</a>
        </div>
    </div>
    
<?php else: ?>
    
    <h2>Anuncio no encontrado</h2>
    <p>Lo sentimos, no pudimos encontrar el anuncio que buscabas.</p>
    
<?php endif; ?>

<?php
require_once 'include/footer.php';
?>