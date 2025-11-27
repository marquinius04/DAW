<?php
$titulo_pagina = "Configurar estilo - PI";
require_once 'include/head.php'; 
require_once 'include/db_connect.php';
require_once 'include/select_options.inc.php'; 

controlar_acceso_privado();

$mysqli = conectar_bd();
$usuario = $_SESSION['usuario'];

// Obtener el estilo actual del usuario
$sql_actual = "SELECT Estilo FROM usuarios WHERE NomUsuario = '$usuario'";
$res = $mysqli->query($sql_actual);
$fila = $res->fetch_assoc();
$estilo_actual_id = $fila['Estilo'];
?>

<h2>Configuración de la cuenta</h2>

<section>
    <h3>Cambiar apariencia</h3>
    <p>Seleccione el estilo visual que desea utilizar en la aplicación:</p>
    
    <form action="respuesta_configurar.php" method="post">
        <label for="estilo">Estilo visual:</label>
        <select name="estilo" id="estilo">
            <?php 
            // Generar opciones desde la tabla estilos
            generar_select_options($mysqli, 'estilos', 'IdEstilo', 'Nombre', $estilo_actual_id); 
            ?>
        </select>
        <br><br>
        <button type="submit">Guardar configuración</button>
    </form>
</section>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>