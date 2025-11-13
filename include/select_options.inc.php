<?php

/**
 * Genera las etiquetas <option> para un desplegable HTML
 * consultando una tabla maestra de la BD.
 */
function generar_select_options($mysqli, $table_name, $id_col, $name_col, $selected_id = null) {
    
    // Usamos comillas invertidas por si los nombres de columnas coinciden con palabras reservadas
    $sql = "SELECT `$id_col`, `$name_col` FROM `$table_name` ORDER BY `$name_col` ASC";
    
    if (!$resultado = $mysqli->query($sql)) {
        echo "<option value=\"\">Error al cargar datos: " . $mysqli->error . "</option>";
        return;
    }

    while ($fila = $resultado->fetch_assoc()) {
        $id = $fila[$id_col];
        $nombre = htmlspecialchars($fila[$name_col]);
        // Comprueba si esta opción debe estar seleccionada
        $selected = ($id == $selected_id) ? 'selected' : '';
        echo "<option value=\"$id\" $selected>$nombre</option>";
    }
    
    $resultado->close();
}
?>