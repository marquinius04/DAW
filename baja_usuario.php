<?php
$titulo_pagina = "Darse de baja - PI";
require_once 'include/head.php'; 
require_once 'include/db_connect.php';
controlar_acceso_privado(); 

$mysqli = conectar_bd();
$uid = $_SESSION['id_usuario'];

// --- OBTENER RESUMEN DE DATOS [cite: 78] ---
// 1. Total Anuncios
$res_anuncios = $mysqli->query("SELECT COUNT(*) as Total FROM anuncios WHERE Usuario = $uid");
$total_anuncios = $res_anuncios->fetch_assoc()['Total'];

// 2. Total Fotos (Join con anuncios del usuario)
$res_fotos = $mysqli->query("SELECT COUNT(*) as Total FROM fotos F JOIN anuncios A ON F.Anuncio = A.IdAnuncio WHERE A.Usuario = $uid");
$total_fotos = $res_fotos->fetch_assoc()['Total'];

// 3. Detalle por anuncio
$sql_detalle = "SELECT A.Titulo, COUNT(F.IdFoto) as NumFotos FROM anuncios A LEFT JOIN fotos F ON A.IdAnuncio = F.Anuncio WHERE A.Usuario = $uid GROUP BY A.IdAnuncio";
$res_detalle = $mysqli->query($sql_detalle);
?>

    <h2>Darse de baja</h2>
    
    <div class="caja-lateral" style="border-color: red; background-color: #fff0f0;">
        <h3>⚠️ Advertencia de seguridad</h3>
        <p>Está a punto de eliminar su cuenta. Esta acción es <strong>irreversible</strong>.</p>
        
        <h4>Se eliminarán los siguientes datos:</h4>
        <ul>
            <li>Su perfil de usuario y configuración.</li>
            <li><strong><?php echo $total_anuncios; ?></strong> Anuncios publicados.</li>
            <li><strong><?php echo $total_fotos; ?></strong> Fotos subidas.</li>
        </ul>

        <?php if ($total_anuncios > 0): ?>
            <p><strong>Detalle de anuncios a eliminar:</strong></p>
            <ul>
                <?php while($row = $res_detalle->fetch_assoc()): ?>
                    <li>"<?= htmlspecialchars($row['Titulo']) ?>" (<?= $row['NumFotos'] ?> fotos)</li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>
    </div>
    
    <br>
    
    <form action="respuesta_baja.php" method="post" style="border: 1px solid #ccc; padding: 20px;">
      <label for="clave_confirmacion">Introduzca su contraseña actual para confirmar:</label>
      <input type="password" id="clave_confirmacion" name="clave_confirmacion" required>
      
      <div style="margin-top: 15px;">
          <button type="submit" style="background-color: #d32f2f;">CONFIRMAR BAJA DEFINITIVA</button>
          <a href="modificar_datos.php" class="button" style="padding: 10px; text-decoration: none;">Cancelar</a>
      </div>
    </form>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>