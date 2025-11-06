<?php

// 1. Iniciar la sesión
// Debe ser lo PRIMERO en ejecutarse.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Incluir datos de usuarios y anuncios
require_once __DIR__ . '/../data/usuarios.php';
require_once __DIR__ . '/../data/anuncios.php'; 

// 3. Gestión de flashdata (mensajes temporales)
// Coge el mensaje de error de la sesión, lo guarda en una variable 
// y lo borra de la sesión para que solo se muestre una vez.
$flash_error = $_SESSION['flash_error'] ?? null;
if ($flash_error) {
    unset($_SESSION['flash_error']);
}

/**
 * Asigna un estilo CSS único a cada usuario.
 */
function get_estilo_por_usuario($usuario) {
    $estilos_usuarios = [
        // Usuario => Fichero CSS
        'a' => 'css/styles.css',         // Estilo principal
        'user1' => 'css/night.css',          // Modo noche
        'admin' => 'css/contrast.css',       // Alto contraste
        'marcos' => 'css/big.css',           // Texto grande
        'gustavo' => 'css/contrast_big.css'  // Contraste + Grande
    ];
    
    // Si el usuario existe en el array, devuelve su estilo.
    // Si no, devuelve el estilo por defecto.
    return $estilos_usuarios[$usuario] ?? 'css/styles.css';
}

// 4. Gestion de "Recordarme" y auto-login por cookie
// Si el usuario NO tiene una sesión activa PERO SÍ tiene las cookies de "recordarme"
if (!isset($_SESSION['usuario']) && isset($_COOKIE['recordar_usuario']) && isset($_COOKIE['recordar_clave'])) {
    
    $usuario_cookie = $_COOKIE['recordar_usuario'];
    $clave_cookie = $_COOKIE['recordar_clave'];

    // Validamos que el usuario y clave de la cookie siguen existiendo
    // $usuarios_permitidos se carga desde 'data/usuarios.php'
    if (array_key_exists($usuario_cookie, $usuarios_permitidos) && $usuarios_permitidos[$usuario_cookie] === $clave_cookie) {
        
        // Si la validacion es correcta, iniciamos la sesión
        $_SESSION['usuario'] = $usuario_cookie;
        
        // Guardamos la "última visita" en la sesión para mostrarla
        if (isset($_COOKIE['ultima_visita_real'])) {
            $_SESSION['ultima_visita'] = $_COOKIE['ultima_visita_real'];
        }
        
        // Actualizamos la cookie de "última visita" con la hora actual
        $expira_visita = time() + (90 * 24 * 60 * 60); // 90 días
        setcookie('ultima_visita_real', date('d/m/Y \a \l\a\s H:i:s'), $expira_visita, '/', '', false, true);

        // Asignamos el estilo llamando a nuestra nueva función
        $_SESSION['estilo_css'] = get_estilo_por_usuario($usuario_cookie);
    }
}


/*
    =================================
    FUNCIONES DE AYUDA
    =================================
*/

/**
 * Comprueba si el usuario NO está logueado.
 * Si no lo está, le redirige a la página de login.
 */
function controlar_acceso_privado() {
    if (!isset($_SESSION['usuario'])) {
        $_SESSION['flash_error'] = "Debe iniciar sesión para acceder a esta página.";
        header("Location: index.php");
        exit();
    }
}

/**
 * Comprueba si el usuario YA está logueado.
 * Si lo está, le redirige a la página principal de logueados.
 */
function controlar_acceso_publico() {
    if (isset($_SESSION['usuario'])) {
        header("Location: index_logueado.php");
        exit();
    }
}

/**
 * Genera el saludo de bienvenida según la hora del servidor.
 */
function get_saludo() {
    if (!isset($_SESSION['usuario'])) return "";

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


/**
 * Obtiene la lista de anuncios visitados desde la cookie.
 */
function get_ultimos_anuncios() {
    $visitados_json = $_COOKIE['anuncios_visitados'] ?? null;
    if ($visitados_json) {
        // Devuelve el array asociativo
        return json_decode($visitados_json, true);
    }
    return []; // Devuelve array vacío si no hay cookie
}

/**
 * Añade un anuncio a la cookie de "últimos visitados".
 * Mantiene un máximo de 4 anuncios y gestiona duplicados.
 */
function add_anuncio_visitado($id, $anuncio) {
    global $anuncios_ficticios; // Para obtener datos si no se pasan
    
    // Si no nos pasan los datos, los buscamos
    if ($anuncio === null) {
         $clave_anuncio = ($id % 2 === 0) ? 'par' : 'impar';
         $anuncio = $anuncios_ficticios[$clave_anuncio] ?? null;
         if ($anuncio === null) return; // No se encontró el anuncio
    }
   
    // 1. Obtener datos actuales
    $lista_visitados = get_ultimos_anuncios();
    
    // 2. Crear el item a guardar 
    $item_nuevo = [
        'id' => $id,
        'foto' => $anuncio['fotos'][0] ?? 'img/default.jpg',
        'titulo' => $anuncio['titulo'],
        'ciudad' => $anuncio['ciudad'],
        'pais' => 'España', // Ficticio
        'precio' => $anuncio['precio']
    ];

    // 3. Quitar duplicados: Si ya estaba, bórralo de su antigua posición 
    foreach ($lista_visitados as $key => $item) {
        if ($item['id'] == $id) {
            unset($lista_visitados[$key]);
        }
    }
    
    // 4. Añadir el item nuevo al FINAL de la lista 
    $lista_visitados[] = $item_nuevo;
    
    // 5. Asegurar que solo hay 4 items: si hay más de 4, quita el primero 
    while (count($lista_visitados) > 4) {
        array_shift($lista_visitados); // array_shift quita el primer elemento
    }
    
    // 6. Convertir a JSON y guardar la cookie por 1 semana 
    $json_visitados = json_encode(array_values($lista_visitados)); 
    $expira = time() + (7 * 24 * 60 * 60); // 1 semana
    
    // httponly = true para que no sea accesible por JS 
    setcookie('anuncios_visitados', $json_visitados, $expira, '/', '', false, true);
}
?>