<?php
// /aviso.php (Página Detalle Anuncio)

// 1. INCLUSIONES (BD y Cabecera)
// (head.php carga sesion.php, que ya tiene session_start() y db_connect.php)
require_once 'include/head.php'; 

// 2. CONTROL DE ACCESO
// (Práctica 8: El detalle del anuncio SÓLO es visible para usuarios registrados)
controlar_acceso_privado();

// 3. OBTENER ID Y CONECTAR A BD
$anuncio_id = (int)($_GET['id'] ?? 0); 
$anuncio = null;

$mysqli = conectar_bd();

if ($anuncio_id > 0) {
    // 4. CONSULTA PRINCIPAL DEL ANUNCIO
    // (Buscamos el anuncio específico por su ID y unimos las tablas maestras)
    $sql_anuncio = "
        SELECT 
            A.*, -- Todos los campos de ANUNCIOS
            P.NomPais,
            TA.NomTAnuncio,
            TV.NomTVivienda,
            U.NomUsuario -- Nombre del usuario que lo publicó
        FROM 
            ANUNCIOS A
        JOIN 
            PAISES P ON A.Pais = P.IdPais
        JOIN 
            TIPOSANUNCIOS TA ON A.TAnuncio = TA.IdTAnuncio
        JOIN 
            TIPOSVIVIENDAS TV ON A.TVivienda = TV.IdTVivienda
        JOIN 
            USUARIOS U ON A.Usuario = U.IdUsuario
        WHERE 
            A.IdAnuncio = ?
    ";
    
    $stmt = $mysqli->prepare($sql_anuncio);
    $stmt->bind_param("i", $anuncio_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $anuncio = $resultado->fetch_assoc();
    $stmt->close();
}

// 5. LÓGICA DE COOKIE "ÚLTIMOS VISITADOS" (Práctica 8)
if ($anuncio) {
    // La función add_anuncio_visitado() (en sesion.php) ya está adaptada para la BD.
    // Le pasamos la conexión y el ID.
    add_anuncio_visitado($mysqli, $anuncio_id);
}

// 6. CONSULTA DE FOTOS SECUNDARIAS (TABLA 'FOTOS')
$fotos_secundarias = [];
if ($anuncio) {
    $sql_fotos = "SELECT Foto, Titulo, Alternativo FROM FOTOS WHERE Anuncio = ?";
    $stmt_fotos = $mysqli->prepare($sql_fotos);
    $stmt_fotos->bind_param("i", $anuncio_id);
    $stmt_fotos->execute();
    $resultado_fotos = $stmt_fotos->get_result();
    while ($foto = $resultado_fotos->fetch_assoc()) {
        $fotos_secundarias[] = $foto;
    }
    $stmt_fotos->close();
}

// Define el título de página dinámicamente
if ($anuncio) {
    $titulo_pagina = $anuncio['Titulo'] . " - PI";
} else {
    $titulo_pagina = "Anuncio no encontrado - PI";
}
?>

<?php if ($anuncio): ?>
    
    <div class="grid-container detalle-anuncio">
        
        <div class="columna-principal">
            
            <h2><?php echo htmlspecialchars($anuncio['Titulo']); ?></h2>
            <p style="margin-bottom: 20px;">Publicado el: <?php echo date('d/m/Y', strtotime($anuncio['FRegistro'])); ?></p>

            <img class="imagen-principal" src="<?php echo htmlspecialchars($anuncio['FPrincipal'] ?? 'img/default.jpg'); ?>" alt="<?php echo htmlspecialchars($anuncio['Alternativo']); ?>">
            
            <h3>Descripción</h3>
            <p><?php echo nl2br(htmlspecialchars($anuncio['Texto'])); ?></p>
            
            <h3>Características</h3>
            <div class="anuncio-detalles">
                <p><strong>Precio:</strong> <?php echo number_format($anuncio['Precio'], 2, ',', '.'); ?> €</p>
                <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($anuncio['Ciudad']); ?>, <?php echo htmlspecialchars($anuncio['NomPais']); ?></p>
                <p><strong>Tipo de Anuncio:</strong> <?php echo htmlspecialchars($anuncio['NomTAnuncio']); ?></p>
                <p><strong>Tipo de Vivienda:</strong> <?php echo htmlspecialchars($anuncio['NomTVivienda']); ?></p>
                
                <p><strong>Dormitorios:</strong> <?php echo htmlspecialchars($anuncio['NHabitaciones']); ?></p>
                <p><strong>Baños:</strong> <?php echo htmlspecialchars($anuncio['NBanyos']); ?></p>
                <p><strong>Superficie:</strong> <?php echo htmlspecialchars($anuncio['Superficie']); ?> m²</p>
                <p><strong>Año:</strong> <?php echo htmlspecialchars($anuncio['Anyo']); ?></p>
                <p><strong>Planta:</strong> <?php echo htmlspecialchars($anuncio['Planta']); ?></p>
                
                <p><strong>Publicado por:</strong> <a href="perfil.php?id=<?php echo $anuncio['Usuario']; ?>"><?php echo htmlspecialchars($anuncio['NomUsuario']); ?></a></p>
            </div>
        </div>

        <div class="columna-lateral">
            <section class="caja-lateral">
                <h3>Más fotos</h3>
                <div class="galeria-extra">
                    <?php 
                    // Muestra las fotos secundarias de la tabla FOTOS
                    if (empty($fotos_secundarias)):
                        echo "<p>No hay más fotos disponibles.</p>";
                    else:
                        foreach ($fotos_secundarias as $foto): 
                    ?>
                        <img src="<?php echo htmlspecialchars($foto['Foto']); ?>" alt="<?php echo htmlspecialchars($foto['Alternativo']); ?>">
                    <?php 
                        endforeach; 
                    endif;
                    ?>
                </div>
            </section>
            
            <a href="mensaje.php?anuncio_id=<?php echo $anuncio_id; ?>" class="btn-contacto" style="margin-bottom: 10px;"><span class="icono">mail</span>Enviar mensaje al anunciante</a>
            
            <a href="ver_fotos.php?anuncio_id=<?php echo $anuncio_id; ?>" class="btn-contacto" style="margin-bottom: 10px;">Ver todas las fotos</a>

            <a href="anyadir_foto.php?anuncio_id=<?php echo $anuncio_id; ?>" class="btn-contacto">➕ Añadir foto a anuncio</a>
        </div>
    </div>
    
<?php else: ?>
    
    <h2>Anuncio no encontrado</h2>
    <p>Lo sentimos, no pudimos encontrar el anuncio que buscabas (ID: <?php echo $anuncio_id; ?>).</p>
    
<?php endif; ?>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>