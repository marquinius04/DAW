<?php
// /include/select_options.inc.php

/**
 * Genera las etiquetas <option> para un desplegable HTML
 * consultando una tabla maestra de la BD, utilizando sentencias preparadas.
 *
 * @param mysqli $mysqli Objeto de conexión a la BD.
 * @param string $table_name Nombre de la tabla (ej. PAISES).
 * @param string $id_col Nombre de la columna ID (ej. IdPais).
 * @param string $name_col Nombre de la columna de texto (ej. NomPais).
 * @param int|null $selected_id (Opcional) El ID que debe aparecer preseleccionado.
 */
function generar_select_options($mysqli, $table_name, $id_col, $name_col, $selected_id = null) {
    
    // NOTA: Para las tablas maestras, la consulta es estática y los nombres de tabla/columna
    // provienen de constantes del desarrollador, pero se usa prepare por consistencia.
    $sql = "SELECT `$id_col`, `$name_col` FROM `$table_name` ORDER BY `$name_col` ASC";
    
    // 1. Preparar la sentencia
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt === false) {
        // En caso de fallo de preparación (ej. tabla no existe)
        echo "<option value=\"\">Error al preparar consulta: " . $mysqli->error . "</option>";
        return;
    }
    
    // 2. Ejecutar (sin parámetros de usuario, pues es una consulta estática)
    $stmt->execute();
    
    // 3. Obtener resultado
    $resultado = $stmt->get_result();
    
    if ($resultado === false) {
        echo "<option value=\"\">Error al obtener resultados.</option>";
        $stmt->close();
        return;
    }

    // 4. Recorrer y generar opciones
    while ($fila = $resultado->fetch_assoc()) {
        $id = $fila[$id_col];
        $nombre = htmlspecialchars($fila[$name_col]);
        // Comprueba si esta opción debe estar seleccionada
        $selected = ($id == $selected_id) ? 'selected' : '';
        echo "<option value=\"$id\" $selected>$nombre</option>";
    }
    
    // 5. Cierre de sentencia
    $stmt->close();
}
?>