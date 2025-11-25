<?php
$titulo_pagina = "Modificar datos - PI";
require_once 'include/head.php'; 
require_once 'include/db_connect.php';
require_once 'include/select_options.inc.php';
controlar_acceso_privado(); 

// Obtener datos del usuario logueado
$mysqli = conectar_bd();
$nom = $_SESSION['usuario'];
$sql = "SELECT * FROM usuarios WHERE NomUsuario = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $nom);
$stmt->execute();
$res = $stmt->get_result();
$datos = $res->fetch_assoc();
?>

    <h2>Modificar mis datos</h2>
    <form action="index_logueado.php" method="post"> <label>Nombre de usuario (No modificable):</label>
      <input type="text" value="<?= htmlspecialchars($datos['NomUsuario']) ?>" disabled>

      <label for="email">Correo electrónico:</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($datos['Email']) ?>">

      <label for="ciudad">Ciudad:</label>
      <input type="text" id="ciudad" name="ciudad" value="<?= htmlspecialchars($datos['Ciudad']) ?>">

      <label for="pais">País:</label>
      <select id="pais" name="pais">
          <?php generar_select_options($mysqli, 'paises', 'IdPais', 'NomPais', $datos['Pais']); ?>
      </select>

      <button type="submit">Guardar cambios</button>
    </form>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>