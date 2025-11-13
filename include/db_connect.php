<?php
/**
 * @return mysqli Conexión a la base de datos MySQL
 */
function conectar_bd() {
    // Definimos la ruta del archivo de configuración 
    $config_path = __DIR__ . '/../config.ini'; 
    
    if (!file_exists($config_path)) {
        die("Error: El archivo de configuración 'config.ini' no se encontró en la ruta esperada.");
    }
    
    // Parsear el archivo .ini 
    $config = parse_ini_file($config_path, true); 
    
    // Obtener parámetros de conexión
    $host = $config['DB']['Server'];
    $user = $config['DB']['User'];
    $password = $config['DB']['Password'];
    $database = $config['DB']['Database'];

    // Conectar usando la interfaz orientada a objetos de mysqli
    $mysqli = @new mysqli($host, $user, $password, $database);

    // Manejo de errores de conexión 
    if ($mysqli->connect_errno) {
        // En caso de error, muestra el mensaje y detiene la ejecución
        die('<p>Error al conectar con la base de datos: ' . $mysqli->connect_error . '</p>'); 
    }
    
    if (!$mysqli->set_charset("utf8")) {
        die('<p>Error al cargar el conjunto de caracteres utf8: ' . $mysqli->error . '</p>');
    }

    return $mysqli;
}
?>