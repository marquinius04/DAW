<?php
/*
    =================================
    sesion.php — Gestor central de sesiones y cookies
    =================================
*/

// 1. INICIAR LA SESIÓN
// Debe ser lo PRIMERO en ejecutarse.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. INCLUIR DATOS (usuarios para validar, anuncios para 'visitados')
require_once __DIR__ . '/../data/usuarios.php';
require_once __DIR__ . '/../data/anuncios.php'; 

// 3. GESTIÓN DE MENSAJES "FLASHDATA"
// Coge el mensaje de error de la sesión, lo guarda en una variable 
// y lo borra de la sesión para que solo se muestre una vez.
$flash_error = $_SESSION['flash_error'] ?? null;
if ($flash_error) {
    unset($_SESSION['flash_error']);
}

// 4. GESTIÓN DE COOKIE "RECORDARME" (Auto-Login)
// [Requisito PDF: Task 1]
// Si el usuario NO tiene una sesión activa PERO SÍ tiene las cookies de "recordarme"
if (!isset($_SESSION['usuario']) && isset($_COOKIE['recordar_usuario']) && isset($_COOKIE['recordar_clave'])) {
    
    $usuario_cookie = $_COOKIE['recordar_usuario'];
    $clave_cookie = $_COOKIE['recordar_clave'];

    // Validamos que el usuario y clave de la cookie siguen existiendo [cite: 88]
    if (array_key_exists($usuario_cookie, $usuarios_permitidos) && $usuarios_permitidos[$usuario_cookie] === $clave_cookie) {
        
        // ¡Validación correcta! Iniciamos la sesión
        $_SESSION['usuario'] = $usuario_cookie;
        
        // Guardamos la "última visita" (la de la cookie) en la sesión para mostrarla 
        if (isset($_COOKIE['ultima_visita_real'])) {
            $_SESSION['ultima_visita'] = $_COOKIE['ultima_visita_real'];
        }
        
        // Actualizamos la cookie de "última visita" con la hora actual
        // Esta cookie se renueva siempre, a diferencia de la de login [cite: 67, 68]
        $expira_visita = time() + (90 * 24 * 60 * 60); // 90 días
        setcookie('ultima_visita_real', date('d/m/Y \a \l\a\s H:i:s'), $expira_visita, '/', '', false, true);

        // Asignamos el estilo (simulado, como pide el PDF) [cite: 142, 143]
        $_SESSION['estilo_css'] = ($usuario_cookie === 'user1') ? 'css/night.css' : 'css/styles.css';
    }
}


/*
    =================================
    FUNCIONES DE AYUDA
    =================================
*/

/**
 * [Requisito PDF: Task 2]
 * Comprueba si el usuario está logueado.
 * Si no lo está, le redirige al index con un error.
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
 * (Para evitar que un usuario logueado vea el login o el registro).
 */
function controlar_acceso_publico() {
    if (isset($_SESSION['usuario'])) {
        header("Location: index_logueado.php");
        exit();
    }
}

/**
 * [Requisito PDF: Task 2]
 * Genera el saludo de bienvenida según la hora del servidor.
 * [cite: 95-99]
 * @return string Saludo (ej: "Buenos días, Pepito")
 */
function get_saludo() {
    if (!isset($_SESSION['usuario'])) return "";

    date_default_timezone_set('Europe/Madrid');
    $hora = (int)date('G');
    $nombre = htmlspecialchars($_SESSION['usuario']);
    
    if ($hora >= 6 && $hora < 12) {
        return "Buenos días, {$nombre}"; // 
    } elseif ($hora >= 12 && $hora < 16) {
        return "Hola, {$nombre}"; // [cite: 97]
    } elseif ($hora >= 16 && $hora < 20) {
        return "Buenas tardes, {$nombre}"; // [cite: 98]
    } else {
        return "Buenas noches, {$nombre}"; // [cite: 99]
    }
}


/**
 * [Requisito PDF: Task 6]
 * Obtiene la lista de anuncios visitados desde la cookie.
 * @return array Array de anuncios visitados.
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
 * [Requisito PDF: Task 6]
 * Añade un anuncio a la cookie de "últimos visitados".
 * Mantiene un máximo de 4 anuncios y gestiona duplicados.
 * [cite: 157, 161, 162]
 *
 * @param int $id ID del anuncio.
 * @param array $anuncio Datos del anuncio (título, precio, etc.)
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
        'pais' => 'España', // Ficticio, como en mis_anuncios.php
        'precio' => $anuncio['precio']
    ];

    // 3. Quitar duplicados: Si ya estaba, bórralo de su antigua posición 
    foreach ($lista_visitados as $key => $item) {
        if ($item['id'] == $id) {
            unset($lista_visitados[$key]);
        }
    }
    
    // 4. Añadir el item nuevo al FINAL de la lista (el más reciente)
    $lista_visitados[] = $item_nuevo;
    
    // 5. Asegurar que solo hay 4 items: si hay más de 4, quita el primero (el más antiguo) [cite: 161]
    while (count($lista_visitados) > 4) {
        array_shift($lista_visitados); // array_shift quita el primer elemento
    }
    
    // 6. Convertir a JSON y guardar la cookie por 1 semana [cite: 164]
    $json_visitados = json_encode(array_values($lista_visitados)); // array_values para reindexar
    $expira = time() + (7 * 24 * 60 * 60); // 1 semana
    
    // httponly = true para que no sea accesible por JS [cite: 802]
    setcookie('anuncios_visitados', $json_visitados, $expira, '/', '', false, true);
}
?>