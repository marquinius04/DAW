<?php
$titulo_pagina = "Mis anuncios - PI";
require_once 'include/head.php'; 
require_once 'include/db_connect.php';
controlar_acceso_privado(); 

$mysqli = conectar_bd();
$uid = $_SESSION['id_usuario'];

$sql = "SELECT IdAnuncio, Titulo, Precio, FPrincipal FROM anuncios WHERE Usuario = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $uid);
$stmt->execute();
$resultado = $stmt->get_result();
?>

    <h2>Mis anuncios publicados</h2>
    
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
      <thead>
        <tr style="background: var(--color-primario); color: white;">
          <th>Foto</th> <th>Título</th> <th>Precio</th> <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($resultado->num_rows > 0): ?>
            <?php while ($anuncio = $resultado->fetch_assoc()): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 10px;">
                        <img src="<?= htmlspecialchars($anuncio['FPrincipal']) ?>" width="80" style="border-radius:4px;">
                    </td>
                    <td>
                        <strong><?= htmlspecialchars($anuncio['Titulo']) ?></strong>
                    </td>
                    <td><?= number_format($anuncio['Precio'], 2) ?> €</td>
                    <td>
                        <a href="aviso.php?id=<?= $anuncio['IdAnuncio'] ?>" class="btn-small"><span class="icono">visibility</span></a>
                        
                        <a href="modificar_anuncio.php?id=<?= $anuncio['IdAnuncio'] ?>" class="btn-small" title="Editar"><span class="icono">edit</span></a>
                        
                        <a href="eliminar_anuncio.php?id=<?= $anuncio['IdAnuncio'] ?>" class="btn-small" style="color: red;" title="Eliminar"><span class="icono">delete</span></a>
                        
                        <br><br>
                        <a href="anyadir_foto.php?anuncio_id=<?= $anuncio['IdAnuncio'] ?>" style="font-size: 0.8em;">+ Foto</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4" style="padding: 20px; text-align: center;">No tienes anuncios publicados.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    
    <br>
    <a href="crear_anuncio.php" class="btn-contacto" style="width: 200px; text-align: center;">Crear nuevo anuncio</a>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>