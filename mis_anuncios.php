<?php
$titulo_pagina = "Mis anuncios - PI";
require_once 'include/head.php'; 
require_once 'include/db_connect.php';
controlar_acceso_privado(); 

$mysqli = conectar_bd();

// Obtener ID del usuario actual por su nombre de sesión
$usuario_nom = $_SESSION['usuario'];
$stmt_u = $mysqli->prepare("SELECT IdUsuario FROM usuarios WHERE NomUsuario = ?");
$stmt_u->bind_param("s", $usuario_nom);
$stmt_u->execute();
$res_u = $stmt_u->get_result();
$fila_u = $res_u->fetch_assoc();
$id_usuario = $fila_u['IdUsuario'];
$stmt_u->close();

// Consultar anuncios de este usuario
$sql = "SELECT A.*, P.NomPais FROM anuncios A JOIN paises P ON A.Pais = P.IdPais WHERE Usuario = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$total_anuncios = $resultado->num_rows;
?>

    <h2>Mis anuncios</h2>
    
    <table style="width: 100%; border-collapse: collapse;">
      <thead>
        <tr>
          <th>Foto</th> <th>Título</th> <th>Precio</th> <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($total_anuncios > 0): ?>
            <?php while ($anuncio = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($anuncio['FPrincipal']) ?>" width="60"></td>
                    <td><a href="aviso.php?id=<?= $anuncio['IdAnuncio'] ?>"><?= htmlspecialchars($anuncio['Titulo']) ?></a></td>
                    <td><?= $anuncio['Precio'] ?> €</td>
                    <td>
                        <a href="ver_mensajes_anuncio.php?id=<?= $anuncio['IdAnuncio'] ?>">Ver mensajes</a> | 
                        <a href="ver_fotos.php?id=<?= $anuncio['IdAnuncio'] ?>&modo=privado">Gestionar fotos</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">No tienes anuncios publicados.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <br>
    <button><a href="crear_anuncio.php" style="color:white; text-decoration:none;">Crear nuevo anuncio</a></button>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>