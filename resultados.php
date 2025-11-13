<?php
require_once 'include/head.php'; 
require_once 'include/db_connect.php'; 
require_once 'include/parser_busqueda.php';

$mysqli = conectar_bd();

$where_clauses = [];
$bind_types = ''; 
$bind_params = []; 
$titulo_resultados = "Resultados de búsqueda";

// --- LÓGICA DE BÚSQUEDA ---

if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    // BÚSQUEDA RÁPIDA CON PARSER
    $titulo_resultados = "Resultados: Búsqueda rápida '" . htmlspecialchars($_GET['q']) . "'";
    $filtros = parsear_busqueda_rapida($mysqli, $_GET['q']);
    
    if ($filtros['tipo_anuncio']) {
        $where_clauses[] = "A.TAnuncio = ?";
        $bind_types .= 'i';
        $bind_params[] = $filtros['tipo_anuncio'];
    }
    if ($filtros['tipo_vivienda']) {
        $where_clauses[] = "A.TVivienda = ?";
        $bind_types .= 'i';
        $bind_params[] = $filtros['tipo_vivienda'];
    }
    if (!empty($filtros['ciudad'])) {
        // Unimos los términos de ciudad 
        $ciudad_like = '%' . implode('%', $filtros['ciudad']) . '%';
        $where_clauses[] = "A.Ciudad LIKE ?";
        $bind_types .= 's';
        $bind_params[] = $ciudad_like;
    }
    // Si no detectó nada, busca genéricamente
    if (empty($where_clauses)) {
         $term = '%' . trim($_GET['q']) . '%';
         $where_clauses[] = "(A.Titulo LIKE ? OR A.Texto LIKE ? OR A.Ciudad LIKE ?)";
         $bind_types .= 'sss';
         $bind_params[] = $term; $bind_params[] = $term; $bind_params[] = $term;
    }

} elseif (isset($_POST['search_submit'])) {
    // BÚSQUEDA AVANZADA
    $titulo_resultados = "Resultados: Búsqueda avanzada";

    if (!empty($_POST['tipo_anuncio'])) {
        $where_clauses[] = "A.TAnuncio = ?";
        $bind_types .= 'i';
        $bind_params[] = $_POST['tipo_anuncio'];
    }
    if (!empty($_POST['tipo_vivienda'])) {
        $where_clauses[] = "A.TVivienda = ?";
        $bind_types .= 'i';
        $bind_params[] = $_POST['tipo_vivienda'];
    }
    if (!empty($_POST['ciudad'])) {
        $where_clauses[] = "A.Ciudad LIKE ?";
        $bind_types .= 's';
        $bind_params[] = "%" . $_POST['ciudad'] . "%";
    }
    if (!empty($_POST['pais'])) {
        $where_clauses[] = "A.Pais = ?";
        $bind_types .= 'i';
        $bind_params[] = $_POST['pais'];
    }
    if (!empty($_POST['precio'])) {
        $where_clauses[] = "A.Precio <= ?";
        $bind_types .= 'd';
        $bind_params[] = $_POST['precio'];
    }
    // Filtro de FECHA 
    if (!empty($_POST['fecha'])) {
        // Buscar anuncios posteriores o iguales a esa fecha
        $where_clauses[] = "A.FRegistro >= ?";
        $bind_types .= 's';
        $bind_params[] = $_POST['fecha'] . " 00:00:00";
    }
} else {
    // Sin criterios
    $where_clauses[] = "1=1"; 
}

// --- CONSTRUCCIÓN DE SQL ---
$sql = "SELECT A.*, P.NomPais FROM ANUNCIOS A JOIN PAISES P ON A.Pais = P.IdPais";
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}
$sql .= " ORDER BY A.FRegistro DESC";

$stmt = $mysqli->prepare($sql);
if (!empty($bind_params)) {
    $stmt->bind_param($bind_types, ...$bind_params);
}
$stmt->execute();
$resultado = $stmt->get_result();
?>

<h2><?php echo $titulo_resultados; ?></h2>

<section>
    <?php if ($resultado && $resultado->num_rows > 0): ?>
        <ul class="listado-anuncios">
        <?php while ($anuncio = $resultado->fetch_assoc()): ?>
            <li>
                <article>
                    <a href="aviso.php?id=<?= $anuncio['IdAnuncio'] ?>">
                        <img src="<?= htmlspecialchars($anuncio['FPrincipal'] ?? 'img/default.jpg') ?>" width="150">
                        <h3><?= htmlspecialchars($anuncio['Titulo']) ?></h3>
                        <p><?= number_format($anuncio['Precio'], 0, ',', '.') ?> €</p>
                        <p><small><?= htmlspecialchars($anuncio['Ciudad']) ?> (<?= htmlspecialchars($anuncio['NomPais']) ?>)</small></p>
                        <p><small>Publicado: <?= date('d/m/Y', strtotime($anuncio['FRegistro'])) ?></small></p>
                    </a>
                </article>
            </li>
        <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No se encontraron resultados.</p>
    <?php endif; ?>
</section>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>