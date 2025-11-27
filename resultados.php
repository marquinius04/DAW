<?php
require_once 'include/sesion.php'; 
require_once 'include/db_connect.php'; 
require_once 'include/head.php'; 

$mysqli = conectar_bd();

// Inicializar variables para la construcción de la consulta
$where_clauses = [];
$bind_types = ''; 
$bind_params = []; 
$search_type = "avanzada"; 

// Determinar el tipo de búsqueda y construir las cláusulas WHERE

// Búsqueda RÁPIDA 
if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    
    $search_type = "rápida";
    $search_term = trim($_GET['q']);

    // Lógica de búsqueda rápida: Buscamos el término en Título, Texto y Ciudad
    // Usamos la entrada del usuario como un parámetro de la sentencia preparada
    $search_string = '%' . $search_term . '%';
    
    // Usamos LOWER() en MySQL para asegurar la búsqueda insensible a mayúsculas
    $where_clauses[] = "(LOWER(A.Titulo) LIKE LOWER(?) OR LOWER(A.Texto) LIKE LOWER(?) OR LOWER(A.Ciudad) LIKE LOWER(?))";
    $bind_types .= 'sss';
    $bind_params[] = $search_string;
    $bind_params[] = $search_string;
    $bind_params[] = $search_string;

} 
// Búsqueda DETALLADA
elseif (isset($_POST['search_submit'])) {
    
    $search_type = "detallada";

 
    if (!empty($_POST['tipo_anuncio'])) {
        $where_clauses[] = "A.TAnuncio = ?";
        $bind_types .= 'i'; // i = integer
        $bind_params[] = (int)$_POST['tipo_anuncio'];
    }

    
    if (!empty($_POST['tipo_vivienda'])) {
        $where_clauses[] = "A.TVivienda = ?";
        $bind_types .= 'i'; 
        $bind_params[] = (int)$_POST['tipo_vivienda'];
    }
    
   
    if (!empty($_POST['ciudad'])) {
        $ciudad_input = '%' . mb_strtolower(trim($_POST['ciudad']), 'UTF-8') . '%';
        $where_clauses[] = "LOWER(A.Ciudad) LIKE ?";
        $bind_types .= 's'; // s = string
        $bind_params[] = $ciudad_input;
    }
    
    if (!empty($_POST['pais'])) {
        $where_clauses[] = "A.Pais = ?";
        $bind_types .= 'i'; 
        $bind_params[] = (int)$_POST['pais'];
    }

    if (!empty($_POST['precio'])) {
        $where_clauses[] = "A.Precio <= ?";
        $bind_types .= 'd'; // d = double 
        $bind_params[] = (float)$_POST['precio'];
    }
    


} else {
    // Si se llega sin parámetros
    $where_clauses[] = "1=0"; // No mostrar nada si no hay criterios
    $search_type = "ninguna";
}


// Construcción de la consulta SQL
$sql_query = "
    SELECT 
        A.IdAnuncio, A.FPrincipal, A.Titulo, A.FRegistro, A.Ciudad, A.Precio, P.NomPais 
    FROM 
        anuncios A
    JOIN 
        paises P ON A.Pais = P.IdPais
    JOIN 
        tiposanuncios TA ON A.TAnuncio = TA.IdTAnuncio
    JOIN 
        tiposviviendas TV ON A.TVivienda = TV.IdTVivienda
";

if (!empty($where_clauses)) {
    $sql_query .= " WHERE " . implode(' AND ', $where_clauses);
}

$sql_query .= " ORDER BY A.FRegistro DESC";


// Preparación y ejecución de la consulta
$stmt = $mysqli->prepare($sql_query);

// Función auxiliar para bind_param por referencia 
function array_by_ref(&$array) {
    $refs = [];
    foreach ($array as $key => $value) {
        $refs[$key] = &$array[$key];
    }
    return $refs;
}

if ($stmt) { 
    if (!empty($bind_params)) {
        // Añadir el string de tipos y vincular por referencia
        array_unshift($bind_params, $bind_types);
        call_user_func_array([$stmt, 'bind_param'], array_by_ref($bind_params));
    }

    $stmt->execute();
    $resultado_anuncios = $stmt->get_result();
} else {
    echo "<p>Error al preparar la consulta de búsqueda: " . $mysqli->error . "</p>";
    $resultado_anuncios = false;
}

?>

<h2>Resultados de la búsqueda (<?= $search_type ?>)</h2>

<section>
    <?php if ($resultado_anuncios && $resultado_anuncios->num_rows > 0): ?>
        <p>Se encontraron <strong><?= $resultado_anuncios->num_rows ?></strong> anuncios que coinciden con su búsqueda.</p>
        
        <ul class="listado-anuncios"> 
            <?php while ($anuncio = $resultado_anuncios->fetch_assoc()): ?>
                <li>
                    <article>
                        <a href="aviso.php?id=<?= $anuncio['IdAnuncio'] ?>">
                            <img src="<?= htmlspecialchars($anuncio['FPrincipal'] ?? 'img/default.jpg') ?>" alt="Foto de <?= htmlspecialchars($anuncio['Titulo']) ?>" width="100">
                            <h3><?= htmlspecialchars($anuncio['Titulo']) ?></h3>
                            <p class="precio"><?= number_format($anuncio['Precio'], 2, ',', '.') ?> €</p>
                            <p class="ubicacion"><?= htmlspecialchars($anuncio['Ciudad']) ?>, <?= htmlspecialchars($anuncio['NomPais']) ?></p>
                        </a>
                    </article>
                </li>
            <?php endwhile; ?>
        </ul>
        
    <?php else: ?>
        <p>No se encontraron anuncios que coincidan con su búsqueda.</p>
    <?php endif; ?>
</section>

<?php

if ($resultado_anuncios) $resultado_anuncios->close();
if ($stmt) $stmt->close();
$mysqli->close();
require_once 'include/footer.php';
?>