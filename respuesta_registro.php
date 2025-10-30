<?php
// Fichero: respuesta_registro.php

// Las redirecciones DEBEN hacerse antes de cualquier contenido HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Recoger y sanear datos
    $usuario = trim($_POST['usuario'] ?? '');
    $clave1 = $_POST['clave'] ?? '';
    $clave2 = $_POST['clave2'] ?? '';
    
    // Aquí recogemos todos los demás datos para mostrarlos después
    $email = htmlspecialchars($_POST['email'] ?? 'N/A');
    $nacimiento = htmlspecialchars("{$_POST['diaNacimiento']}/{$_POST['mesNacimiento']}/{$_POST['anyoNacimiento']}" ?? 'N/A');
    $sexo = htmlspecialchars($_POST['sexo'] ?? 'N/A');

    $error_mensaje = "";

    // 2. Validación PHP: Campos Vacíos
    if (empty($usuario)) {
        $error_mensaje = "El nombre de usuario es obligatorio.";
    } elseif (empty($clave1) || empty($clave2)) {
        $error_mensaje = "Debe introducir y repetir la contraseña.";
    } 
    // 3. Validación PHP: Coincidencia de Claves
    elseif ($clave1 !== $clave2) {
        $error_mensaje = "Las contraseñas no coinciden.";
    }

    // Si hay un error, redirigir a registro.php usando la URL
    if ($error_mensaje !== "") {
        $error_url = urlencode($error_mensaje);
        header("Location: registro.php?error={$error_url}");
        exit();
    }
    
    // --- Lógica de Éxito: Si pasa la validación ---
    
    // NOTA: La práctica NO exige que guardes el usuario en datos/usuarios.php, 
    // solo exige mostrar los datos y la validación.
    
    // Incluimos la plantilla aquí, ya que no hay redirección
    $titulo_pagina = "Registro Exitoso";
    require_once 'include/head.php'; 
    ?>
    
    <h2>✅ ¡Registro Exitoso!</h2>
    <p>La validación se ha completado. Tus datos han sido recibidos correctamente:</p>
    
    <section style="border: 1px solid #28a745; padding: 15px; background-color: #d4edda;">
        <h3>Datos Introducidos:</h3>
        <ul>
            <li>Nombre de Usuario: <?php echo htmlspecialchars($usuario); ?></li>
            <li>Email: <?php echo $email; ?></li>
            <li>Fecha de Nacimiento: <?php echo $nacimiento; ?></li>
            <li>Sexo: <?php echo $sexo; ?></li>
            <li>Contraseña: [*Oculta por seguridad*]</li>
        </ul>
    </section>

    <p>Puedes <a href="index.php">volver a la página de inicio</a> para acceder.</p>
    
    <?php
    require_once 'include/footer.php';
    exit();
} else {
    // Si se accede directamente sin POST, redirigir al formulario
    header("Location: registro.php");
    exit();
}
?>