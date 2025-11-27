<?php

/**
 * Genera las etiquetas <option> para un desplegable HTML
 * consultando una tabla maestra de la BD, utilizando sentencias preparadas.
 */
function generar_select_options($mysqli, $table_name, $id_col, $name_col, $selected_id = null) {
    $sql = "SELECT `$id_col`, `$name_col` FROM `$table_name` ORDER BY `$name_col` ASC";
    
    // Preparar la sentencia
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt === false) {
        // En caso de fallo de preparación 
        echo "<option value=\"\">Error al preparar consulta: " . $mysqli->error . "</option>";
        return;
    }
    
    // Ejecutar
    $stmt->execute();
    
    // Obtener resultado
    $resultado = $stmt->get_result();
    
    if ($resultado === false) {
        echo "<option value=\"\">Error al obtener resultados.</option>";
        $stmt->close();
        return;
    }

    // Recorrer y generar opciones
    while ($fila = $resultado->fetch_assoc()) {
        $id = $fila[$id_col];
        $nombre = htmlspecialchars($fila[$name_col]);
        // Comprueba si esta opción debe estar seleccionada
        $selected = ($id == $selected_id) ? 'selected' : '';
        echo "<option value=\"$id\" $selected>$nombre</option>";
    }
    
    // Cierre de sentencia
    $stmt->close();
}
?>