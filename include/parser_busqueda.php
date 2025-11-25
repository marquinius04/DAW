<?php

function parsear_busqueda_rapida($mysqli, $query) {
    $terminos = explode(' ', trim($query));
    $filtros = [
        'tipo_anuncio' => null,
        'tipo_vivienda' => null,
        'ciudad' => []
    ];
    
    // Palabras vacías a ignorar 
    $stopwords = ['un', 'una', 'en', 'de', 'el', 'la'];

    foreach ($terminos as $termino) {
        if (in_array(strtolower($termino), $stopwords)) continue;
        if (empty($termino)) continue;

        $encontrado = false;

        // 1. Buscar en tiposanuncios 
        if (!$filtros['tipo_anuncio']) {
            $sql = "SELECT IdTAnuncio FROM tiposanuncios WHERE NomTAnuncio LIKE ?";
            $stmt = $mysqli->prepare($sql);
            $param = "%$termino%";
            $stmt->bind_param("s", $param);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                $filtros['tipo_anuncio'] = $row['IdTAnuncio'];
                $encontrado = true;
            }
            $stmt->close();
        }

        // 2. Buscar en tiposviviendas 
        if (!$encontrado && !$filtros['tipo_vivienda']) {
            $sql = "SELECT IdTVivienda FROM tiposviviendas WHERE NomTVivienda LIKE ?";
            $stmt = $mysqli->prepare($sql);
            $param = "%$termino%";
            $stmt->bind_param("s", $param);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                $filtros['tipo_vivienda'] = $row['IdTVivienda'];
                $encontrado = true;
            }
            $stmt->close();
        }

        // 3. Si no es tipo, asumimos que es parte de la Ciudad
        if (!$encontrado) {
            $filtros['ciudad'][] = $termino;
        }
    }
    
    return $filtros;
}
?>