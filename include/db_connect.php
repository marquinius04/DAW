<?php
// /include/db_connect.php

/**
 * Conecta con el servidor de MariaDB (MySQL) y selecciona la base de datos 'pibd'.
 * Utiliza los parámetros de conexión de 'config.ini'.
 *
 * @return mysqli El objeto de conexión mysqli.
 */
function conectar_bd() {
    // Definimos la ruta del archivo de configuración (está un nivel arriba)
    $config_path = __DIR__ . '/../config.ini'; 
    
    if (!file_exists($config_path)) {
        // En un caso real, manejarías este error de forma más elegante
        die("Error: El archivo de configuración 'config.ini' no se encontró en la ruta esperada.");
    }
    
    // Parsear el archivo .ini [cite: 1468]
    $config = parse_ini_file($config_path, true); 
    
    // Obtener parámetros de conexión
    $host = $config['DB']['Server'];
    $user = $config['DB']['User'];
    $password = $config['DB']['Password'];
    $database = $config['DB']['Database'];

    // Conectar usando la interfaz orientada a objetos de mysqli
    // Usamos @ para suprimir errores y manejarlo con if ($mysqli->connect_errno)
    $mysqli = @new mysqli($host, $user, $password, $database);

    // Manejo de errores de conexión [cite: 1178, 1180]
    if ($mysqli->connect_errno) {
        // En caso de error, muestra el mensaje y detiene la ejecución
        die('<p>Error al conectar con la base de datos: ' . $mysqli->connect_error . '</p>'); 
    }
    
    // Establecer el juego de caracteres a UTF-8 para evitar problemas de codificación [cite: 1394]
    // Es lo más cómodo y evita muchos problemas[cite: 1392].
    if (!$mysqli->set_charset("utf8")) {
        die('<p>Error al cargar el conjunto de caracteres utf8: ' . $mysqli->error . '</p>');
    }

    return $mysqli;
}
?>