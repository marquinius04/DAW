<?php
// /resultados.php

// 1. INCLUSIONES
require_once 'include/sesion.php'; 
require_once 'include/db_connect.php'; 
// No incluimos 'search_parser.inc.php'
require_once 'include/head.php'; 

$mysqli = conectar_bd();

// 2. INICIALIZACIÓN DE LA CONSULTA
$where_clauses = [];
$bind_types = ''; // String para los tipos de bind_param (ej. "issd")
$bind_params = []; // Array para los valores de bind_param
$search_type = "avanzada"; // Tipo de búsqueda por defecto

// 3. DETERMINAR EL TIPO DE BÚSQUEDA (RÁPIDA O DETALLADA)

// A. BÚSQUEDA RÁPIDA (desde index.php, usa GET y el campo 'q')
if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    
    $search_type = "rápida";
    $search_term = trim($_GET['q']);

    // Lógica de búsqueda rápida (simplificada, sin parser)
    // Buscamos el término en Título, Texto y Ciudad (insensible a mayúsculas)
    $search_string = '%' . $search_term . '%';
    
    $where_clauses[] = "(LOWER(A.Titulo) LIKE LOWER(?) OR LOWER(A.Texto) LIKE LOWER(?) OR LOWER(A.Ciudad) LIKE LOWER(?))";
    $bind_types .= 'sss';
    $bind_params[] = $search_string;
    $bind_params[] = $search_string;
    $bind_params[] = $search_string;

}
// --- AÑADE ESTE BLOQUE ---
// A.2. BÚSQUEDA RÁPIDA POR CIUDAD (desde index_logueado.php, usa GET y 'ciudad')
elseif (isset($_GET['ciudad']) && !empty(trim($_GET['ciudad']))) {

    $search_type = "rápida (ciudad)";
    
    // Ciudad (String, insensible a mayúsculas)
    $ciudad_input = '%' . mb_strtolower(trim($_GET['ciudad']), 'UTF-8') . '%';
    $where_clauses[] = "LOWER(A.Ciudad) LIKE ?";
    $bind_types .= 's'; // s = string
    $bind_params[] = $ciudad_input;
}
// B. BÚSQUEDA DETALLADA (desde busqueda.php, usa POST)
elseif (isset($_POST['search_submit'])) {
    
    $search_type = "detallada";

    // Tipo de Anuncio (ID numérico)
    if (!empty($_POST['tipo_anuncio'])) {
        $where_clauses[] = "A.TAnuncio = ?";
        $bind_types .= 'i'; // i = integer
        $bind_params[] = (int)$_POST['tipo_anuncio'];
    }

    // Tipo de Vivienda (ID numérico)
    if (!empty($_POST['tipo_vivienda'])) {
        $where_clauses[] = "A.TVivienda = ?";
        $bind_types .= 'i'; 
        $bind_params[] = (int)$_POST['tipo_vivienda'];
    }
    
    // Ciudad (String, insensible a mayúsculas)
    if (!empty($_POST['ciudad'])) {
        // Usamos LOWER() en la BD y mb_strtolower en PHP para búsquedas insensibles
        $ciudad_input = '%' . mb_strtolower(trim($_POST['ciudad']), 'UTF-8') . '%';
        $where_clauses[] = "LOWER(A.Ciudad) LIKE ?";
        $bind_types .= 's'; // s = string
        $bind_params[] = $ciudad_input;
    }
    
    // País (ID numérico) - (Usamos 'pais' como en tu busqueda.php)
    if (!empty($_POST['pais'])) {
        $where_clauses[] = "A.Pais = ?";
        $bind_types .= 'i'; 
        $bind_params[] = (int)$_POST['pais'];
    }

    // Precio Máximo (Número)
    if (!empty($_POST['precio'])) {
        $where_clauses[] = "A.Precio <= ?";
        $bind_types .= 'd'; // d = double (para campos DECIMAL o REAL)
        $bind_params[] = (float)$_POST['precio'];
    }

} else {
    // Si se llega sin parámetros GET o POST, no se aplica filtro
    if (empty($where_clauses)) {
        $where_clauses[] = "1=0"; // No mostrar nada si no se busca nada
        $search_type = "ninguna";
    }
}


// 4. CONSTRUIR LA CONSULTA SQL FINAL
$sql_query = "
    SELECT 
        A.IdAnuncio, A.FPrincipal, A.Titulo, A.FRegistro, A.Ciudad, A.Precio, P.NomPais 
    FROM 
        ANUNCIOS A
    JOIN 
        PAISES P ON A.Pais = P.IdPais
    -- Necesitamos estos JOINs por si la búsqueda rápida incluye tipos
    JOIN 
        TIPOSANUNCIOS TA ON A.TAnuncio = TA.IdTAnuncio
    JOIN 
        TIPOSVIVIENDAS TV ON A.TVivienda = TV.IdTVivienda
";

if (!empty($where_clauses)) {
    $sql_query .= " WHERE " . implode(' AND ', $where_clauses);
}

$sql_query .= " ORDER BY A.FRegistro DESC";


// 5. PREPARAR Y EJECUTAR LA CONSULTA
$stmt = $mysqli->prepare($sql_query);

// Función auxiliar para bind_param (necesaria para arrays dinámicos)
function array_by_ref(&$array) {
    $refs = [];
    foreach ($array as $key => $value) {
        $refs[$key] = &$array[$key];
    }
    return $refs;
}

if ($stmt) { // Comprueba si la preparación fue exitosa
    if (!empty($bind_params)) {
        // Añadimos el string de tipos al inicio del array de parámetros
        array_unshift($bind_params, $bind_types);
        // Llamamos a bind_param
        call_user_func_array([$stmt, 'bind_param'], array_by_ref($bind_params));
    }

    $stmt->execute();
    $resultado_anuncios = $stmt->get_result();
} else {
    // Error en la preparación de la consulta
    echo "<p>Error al preparar la consulta de búsqueda: " . $mysqli->error . "</p>";
    $resultado_anuncios = false;
}

?>

<h2>Resultados de la Búsqueda (<?= $search_type ?>)</h2>

<section>
    <?php if ($resultado_anuncios && $resultado_anuncios->num_rows > 0): ?>
        <p>Se encontraron <strong><?= $resultado_anuncios->num_rows ?></strong> anuncios que coinciden con su búsqueda.</p>
        
        <ul class="listado-anuncios"> <?php while ($anuncio = $resultado_anuncios->fetch_assoc()): ?>
                <li>
                    <article>
                        <a href="detalle_anuncio.php?id=<?= $anuncio['IdAnuncio'] ?>">
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
// 7. CIERRE DE RECURSOS
if ($resultado_anuncios) $resultado_anuncios->close();
if ($stmt) $stmt->close();
$mysqli->close();
require_once 'include/footer.php';
?>