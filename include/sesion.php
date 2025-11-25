<?php
// /include/sesion.php

// Inicia la sesión de PHP
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. INCLUSIONES NECESARIAS
// Se asume que db_connect.php y flashdata.inc.php existen en el mismo directorio 'include/'
require_once __DIR__ . '/db_connect.php'; 
require_once __DIR__ . '/flashdata.inc.php'; 

// -------------------------------------------------------------
// 2. LÓGICA DE AUTOLOGIN por COOKIE (Recuérdame)
// -------------------------------------------------------------
if (!isset($_SESSION['usuario']) && isset($_COOKIE['usuario_pi']) && isset($_COOKIE['clave_pi'])) {

    $mysqli = conectar_bd();

    $usuario_cookie = $_COOKIE['usuario_pi'];
    $clave_cookie_plano = $_COOKIE['clave_pi']; // Contraseña en texto plano de la cookie

    // Consulta para obtener el hash de la clave y otros datos
    $sql = "SELECT IdUsuario, Clave, NomUsuario, Estilo FROM USUARIOS WHERE NomUsuario = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $usuario_cookie);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $user = $resultado->fetch_assoc();
        
        // MODIFICACIÓN CRUCIAL: Compara la clave de la cookie (plano) con el hash de la BD
        if (password_verify($clave_cookie_plano, $user['Clave'])) {
            
            // ÉXITO: Iniciar sesión automática
            $_SESSION['usuario'] = $user['NomUsuario'];
            $_SESSION['id_usuario'] = $user['IdUsuario'];
            $_SESSION['estilo'] = $user['Estilo'];

            // Renovación de la cookie para extender la validez (90 días)
            $dias = 90;
            $expire = time() + ($dias * 24 * 60 * 60); 
            
            setcookie('usuario_pi', $usuario_cookie, $expire, '/');
            setcookie('clave_pi', $clave_cookie_plano, $expire, '/');
            setcookie('ultima_visita_real', date('d/m/Y \a \l\a\s H:i:s'), $expire, '/', '', false, true);

        } else {
            // Fallo: Hash no coincide (la cookie es inválida)
            setcookie('usuario_pi', '', time() - 3600, '/');
            setcookie('clave_pi', '', time() - 3600, '/');
            setcookie('ultima_visita_real', '', time() - 3600, '/');
        }
    } else {
        // Fallo: Usuario no existe (eliminar cookies por seguridad)
        setcookie('usuario_pi', '', time() - 3600, '/');
        setcookie('clave_pi', '', time() - 3600, '/');
        setcookie('ultima_visita_real', '', time() - 3600, '/');
    }
    
    $mysqli->close(); 
}

// -------------------------------------------------------------
// 3. FUNCIONES AUXILIARES (CONTROL DE ACCESO Y SALUDO)
// -------------------------------------------------------------

/**
 * Controla el acceso a páginas privadas. Redirige a index.php si no hay sesión.
 */
function controlar_acceso_privado() {
    if (!isset($_SESSION['usuario'])) {
        // Usa set_flashdata() para el mensaje de error (Práctica 8)
        require_once __DIR__ . '/flashdata.inc.php'; 
        set_flashdata('error', 'Debe iniciar sesión para acceder a esta página.');
        header('Location: index.php');
        exit();
    }
}

/**
 * Comprueba si el usuario ya está logueado
 * Si lo está, le redirige a la página principal de la parte privada
 */
function controlar_acceso_publico() {
    if (isset($_SESSION['usuario'])) {
        header('Location: index_logueado.php');
        exit();
    }
}

/**
 * Genera un saludo dinámico según la hora del servidor (Práctica 8).
 */
function get_saludo() {
    if (isset($_SESSION['usuario'])) {
        date_default_timezone_set('Europe/Madrid');
        $hora = (int)date('G');
        $nombre = htmlspecialchars($_SESSION['usuario']);

        if ($hora >= 6 && $hora < 12) {
            $saludo = 'Buenos días';
        } elseif ($hora >= 12 && $hora < 16) {
            $saludo = 'Hola';
        } elseif ($hora >= 16 && $hora < 20) {
            $saludo = 'Buenas tardes';
        } else {
            $saludo = 'Buenas noches';
        }
        return "{$saludo}, {$nombre}";
    }
    return '';
}

/**
 * Obtiene la lista de anuncios visitados desde la cookie.
 */
function get_ultimos_anuncios() {
    $visitados_json = $_COOKIE['anuncios_visitados'] ?? null;
    if ($visitados_json) {
        return json_decode($visitados_json, true);
    }
    return []; 
}

/**
 * Añade un anuncio al historial de visitas almacenado en una cookie.
 * * @param mysqli $mysqli Objeto de conexión a la base de datos.
 * @param int $id El ID del anuncio visitado.
 */
function add_anuncio_visitado($mysqli, $id) {
    if ($id <= 0) return;
    
    // 1. Obtener datos del anuncio de la BD (solo lo necesario para la cookie)
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

    // 2. Lógica de reordenación de la cookie (Práctica 8)
    $lista_visitados = get_ultimos_anuncios();
    
    // Crear el item nuevo (adaptado a la BD)
    $item_nuevo = [
        'id' => $id,
        'foto' => $anuncio_data['FPrincipal'] ?? 'img/default.jpg',
        'titulo' => $anuncio_data['Titulo'],
        'ciudad' => $anuncio_data['Ciudad'],
        'pais' => $anuncio_data['NomPais'],
        'precio' => $anuncio_data['Precio']
    ];

    // Quita duplicados: Si ya estaba, lo borra de su antigua posición
    foreach ($lista_visitados as $key => $item) {
        if ($item['id'] == $id) {
            unset($lista_visitados[$key]);
            break;
        }
    }
    
    // Añade el item nuevo al final de la lista
    $lista_visitados[] = $item_nuevo;
    
    // Limita a 4 items
    while (count($lista_visitados) > 4) {
        array_shift($lista_visitados);
    }
    
    // 3. Guardar en la cookie
    $json_visitados = json_encode(array_values($lista_visitados)); 
    $expira = time() + (7 * 24 * 60 * 60); // 1 semana
    
    // httponly = true para que no sea accesible por js
    setcookie('anuncios_visitados', $json_visitados, $expira, '/', '', false, true);
}