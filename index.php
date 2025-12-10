<?php

// GESTIÓN DE SESIÓN, CONEXIÓN Y ERRORES
require_once 'include/sesion.php'; 
require_once 'include/db_connect.php'; 
require_once 'include/flashdata.inc.php'; 

// Recupera el mensaje de error de login 
$mensaje_error = get_flashdata('error');

// Configuración de la página
$titulo_pagina = "Acceso y anuncios recientes";
$body_id = "loginPage"; 
$menu_tipo = 'publico';

// Si el usuario ya está logueado, se le redirige a la zona privada
controlar_acceso_publico();

$mysqli = conectar_bd();

// -------------------------------------------------------------
// 1. LÓGICA DE ÚLTIMOS 5 ANUNCIOS 
// -------------------------------------------------------------
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

$stmt = $mysqli->prepare($sql_anuncios);
$resultado_anuncios = null;
$error_anuncios = null;

if ($stmt === false) {
    $error_anuncios = "Error al preparar la consulta de anuncios: " . $mysqli->error;
} else {
    $stmt->execute();
    $resultado_anuncios = $stmt->get_result();
    $stmt->close();
}

// -------------------------------------------------------------
// 2. LÓGICA DEL ANUNCIO ESCOGIDO (FICHERO DE TEXTO)
// -------------------------------------------------------------
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
                
                $sql_destacado = "SELECT A.IdAnuncio, A.Titulo, A.FPrincipal, A.Precio, A.Ciudad, P.NomPais 
                                  FROM anuncios A 
                                  JOIN paises P ON A.Pais = P.IdPais 
                                  WHERE A.IdAnuncio = $id_candidato";
                $res_dest = $mysqli->query($sql_destacado);
                
                if ($res_dest && $res_dest->num_rows > 0) {
                    $anuncio_destacado = $res_dest->fetch_assoc();
                }
            }
            $intentos++;
            unset($lineas[$indice_aleatorio]);
            
        } while ($anuncio_destacado === null && count($lineas) > 0 && $intentos < $max_intentos);
    }
}

// -------------------------------------------------------------
// 3. LÓGICA DEL CONSEJO (JSON) - ¡NUEVO!
// -------------------------------------------------------------
$fichero_consejos = __DIR__ . '/data/consejos.json';
$consejo_del_dia = null;

if (file_exists($fichero_consejos)) {
    $json_content = file_get_contents($fichero_consejos);
    $consejos = json_decode($json_content, true); // true para array asociativo
    
    if ($consejos && count($consejos) > 0) {
         // Elegir un consejo aleatorio
         $consejo_del_dia = $consejos[array_rand($consejos)];
    }
}
?>

<?php require_once 'include/head.php'; ?>

<?php if ($mensaje_error): ?>
    <p style="color: red; padding: 10px; border: 1px solid red; background-color: #ffeaea; margin-bottom: 20px;">
        ⚠️ Error de Acceso: <?= htmlspecialchars($mensaje_error) ?>
    </p>
<?php endif; ?>

<?php 
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
            <input type="hidden" name="recordarme" value="on"> 
            <button type="submit">Entrar</button>
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
        <input type="text" id="q" name="q" placeholder="Ej: local alquiler alicante">
        <button type="submit">Buscar</button>
      </form>
    </section>

    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        
        <?php if ($anuncio_destacado): ?>
        <section style="flex: 2; min-width: 300px; border: 2px solid #ff8c00; background-color: #fff8e1;">
            <h2><span class="icono">stars</span> Anuncio seleccionado</h2>
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
        <section style="flex: 1; min-width: 250px; border: 2px solid #4caf50; background-color: #e8f5e9;">
            <h2><span class="icono" style="color: #2e7d32;">lightbulb</span> Consejo del día</h2>
            
            <p><strong>Categoría:</strong> <?= htmlspecialchars($consejo_del_dia['categoria']) ?></p>
            
            <?php 
                // Color según importancia
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
      <h2>Últimos 5 anuncios</h2>
      
      <?php if ($error_anuncios): ?>
          <p style="color: red;"><?= $error_anuncios ?></p>
      <?php elseif ($resultado_anuncios && $resultado_anuncios->num_rows > 0): ?>
          <ul>
              <?php while ($anuncio = $resultado_anuncios->fetch_assoc()): ?>
                  <li>
                      <article>
                          <a href="aviso.php?id=<?= $anuncio['IdAnuncio'] ?>"> 
                              <img src="<?= htmlspecialchars($anuncio['FPrincipal'] ?? 'img/default.jpg') ?>" alt="Foto de <?= htmlspecialchars($anuncio['Titulo']) ?>" width="100">
                              <h3><?= htmlspecialchars($anuncio['Titulo']) ?></h3>
                              <p><?= number_format($anuncio['Precio'], 2, ',', '.') ?> €</p>
                              <p>Ubicación: <?= htmlspecialchars($anuncio['Ciudad']) ?>, <?= htmlspecialchars($anuncio['NomPais']) ?></p>
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