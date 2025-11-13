<?php

// Inicia la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluye la conexión a BD y el nuevo sistema de Flashdata
require_once __DIR__ . '/db_connect.php'; 
require_once __DIR__ . '/flashdata.inc.php'; 

/**
 * Asigna un estilo CSS único a cada usuario consultando la BD
 */
function get_estilo_por_usuario_bd($mysqli, $usuario) {
    $default_style = 'css/styles.css'; // Estilo por defecto

    $stmt = $mysqli->prepare("
        SELECT E.Fichero 
        FROM USUARIOS U 
        JOIN ESTILOS E ON U.Estilo = E.IdEstilo 
        WHERE U.NomUsuario = ?
    ");

    if ($stmt === false) {
        error_log("Error al preparar consulta de estilo: " . $mysqli->error);
        return $default_style;
    }

    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        $fichero_css = htmlspecialchars($fila['Fichero']);
    } else {
        $fichero_css = $default_style;
    }

    $stmt->close();
    $resultado->close();
    
    return $fichero_css;
}

// Si el usuario no tiene una sesión activa pero sí tiene las cookies de "recordarme"
if (!isset($_SESSION['usuario']) && isset($_COOKIE['recordar_usuario']) && isset($_COOKIE['recordar_clave'])) {
    
    $usuario_cookie = $_COOKIE['recordar_usuario'];
    $clave_cookie = $_COOKIE['recordar_clave'];

    $mysqli = conectar_bd();

    $stmt = $mysqli->prepare("
        SELECT NomUsuario, Estilo, Clave 
        FROM USUARIOS 
        WHERE NomUsuario = ? AND Clave = ?
    ");
    
    if ($stmt === false) {
        $mysqli->close();
        goto end_recuerdame; 
    }

    $stmt->bind_param("ss", $usuario_cookie, $clave_cookie);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        $_SESSION['usuario'] = $fila['NomUsuario'];
        
        if (isset($_COOKIE['ultima_visita_real'])) {
            $_SESSION['ultima_visita'] = $_COOKIE['ultima_visita_real'];
        }
        
        $expira_visita = time() + (90 * 24 * 60 * 60); // 90 días
        setcookie('ultima_visita_real', date('d/m/Y \a \l\a\s H:i:s'), $expira_visita, '/', '', false, true);

        $fichero_css = get_estilo_por_usuario_bd($mysqli, $fila['NomUsuario']);
        $_SESSION['estilo_css'] = $fichero_css;
    }
    
    $stmt->close();
    $resultado->close();
    $mysqli->close();

    end_recuerdame:
}

/**
 * Obtiene la lista de anuncios visitados desde la cookie
 */
function get_ultimos_anuncios() {
    $visitados_json = $_COOKIE['anuncios_visitados'] ?? null;
    if ($visitados_json) {
        // Devuelve el array si ha visitado anuncios antes
        return json_decode($visitados_json, true);
    }
    // Devuelve array vacío si no hay cookie
    return []; 
}

/**
 * Añade un anuncio a la cookie de "últimos visitados"
 * Mantiene un máximo de 4 anuncios y gestiona duplicados
 */
function add_anuncio_visitado($mysqli, $id) {
    
    $sql_anuncio = "
        SELECT A.Titulo, A.Ciudad, A.Precio, P.NomPais, A.FPrincipal
        FROM ANUNCIOS A
        JOIN PAISES P ON A.Pais = P.IdPais
        WHERE A.IdAnuncio = ?
    ";
    
    $stmt = $mysqli->prepare($sql_anuncio);
    if ($stmt === false) return;

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $anuncio_data = $resultado->fetch_assoc();
    
    $stmt->close();

    if (!$anuncio_data) return; 
    
    // Obtiene los datos actuales 
    $lista_visitados = get_ultimos_anuncios(); 
    
    $item_nuevo = [
        'id' => $id,
        'foto' => $anuncio_data['FPrincipal'] ?? 'img/default.jpg',
        'titulo' => $anuncio_data['Titulo'],
        'ciudad' => $anuncio_data['Ciudad'],
        'pais' => $anuncio_data['NomPais'],
        'precio' => $anuncio_data['Precio']
    ];

    foreach ($lista_visitados as $key => $item) {
        if ($item['id'] == $id) {
            unset($lista_visitados[$key]);
        }
    }
    
    $lista_visitados[] = $item_nuevo;
    
    while (count($lista_visitados) > 4) {
        array_shift($lista_visitados);
    }
    
    $json_visitados = json_encode(array_values($lista_visitados)); 
    $expira = time() + (7 * 24 * 60 * 60); // 1 semana
    
    setcookie('anuncios_visitados', $json_visitados, $expira, '/', '', false, true);
}


/**
 * Comprueba si el usuario está logueado
 * Si no lo está, le redirige a la página de login
 */
function controlar_acceso_privado() {
    if (!isset($_SESSION['usuario'])) {
        set_flashdata('error', "Debe iniciar sesión para acceder a esta página");
        header("Location: index.php");
        exit();
    }
}

/**
 * Comprueba si el usuario ya está logueado
 * Si lo está, le redirige a la página principal de la parte privada
 */
function controlar_acceso_publico() {
    if (isset($_SESSION['usuario'])) {
        header("Location: index_logueado.php");
        exit();
    }
}

/**
 * Genera el saludo de bienvenida según la hora del servidor
 */
function get_saludo() {
    if (!isset($_SESSION['usuario'])) return "";

    // Establece la zona horaria
    date_default_timezone_set('Europe/Madrid');
    $hora = (int)date('G');
    $nombre = htmlspecialchars($_SESSION['usuario']);
    
    if ($hora >= 6 && $hora < 12) {
        return "Buenos días, {$nombre}"; 
    } elseif ($hora >= 12 && $hora < 16) {
        return "Hola, {$nombre}"; 
    } elseif ($hora >= 16 && $hora < 20) {
        return "Buenas tardes, {$nombre}"; 
    } else {
        return "Buenas noches, {$nombre}"; 
    }
}
?>