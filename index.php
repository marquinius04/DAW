<?php
$titulo_pagina = "Acceso y Anuncios Recientes";
$body_id = "loginPage"; 
$menu_tipo = 'publico';

// [MODIFICADO]
// 1. Incluimos head.php (que ya incluye sesion.php)
require_once 'include/head.php'; 

// [MODIFICADO]
// 2. Si el usuario ya está logueado (por sesión o cookie), redirigir a la zona privada
controlar_acceso_publico();

// [MODIFICADO]
// 3. El gestor de errores flashdata está ahora en head.php
// Ya no necesitamos el bloque if (isset($_GET['error']))
?>

<?php 
// [MODIFICADO]
// [Requisito PDF: Task 1 - Figura 2]
// Mostrar bienvenida si el usuario está siendo recordado 
if (isset($_COOKIE['recordar_usuario']) && isset($_COOKIE['ultima_visita_real'])): 
    $usuario_recordado = htmlspecialchars($_COOKIE['recordar_usuario']);
    $ultima_visita = htmlspecialchars($_COOKIE['ultima_visita_real']);
?>
    <section style="background-color: #e5efff; border: 1px solid var(--color-primario);">
        <h2>Bienvenido de nuevo, <?php echo $usuario_recordado; ?></h2>
        <p>Su última visita fue el <?php echo $ultima_visita; ?>.</p>
        <p>Pulse 'Entrar' para acceder directamente o <a href="logout.php">pulse aquí si no es usted</a>.</p>
        
        <form action="respuesta_login.php" method="post" style="padding: 0; box-shadow: none;">
            <input type="hidden" name="usuario" value="<?php echo $usuario_recordado; ?>">
            <input type="hidden" name="clave" value="<?php echo htmlspecialchars($_COOKIE['recordar_clave']); ?>">
            <input type="hidden" name="recordarme" value="on"> <button type="submit">Entrar</button>
        </form>
    </section>

<?php else: ?>

    <section>
      <h2>Acceso de usuario</h2>
      <form action="respuesta_login.php" method="post">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" >
        <span id="usuarioError" class="error"></span>
        <label for="clave">Contraseña:</label>
        <input type="password" id="clave" name="clave" >
        <span id="claveError" class="error"></span> 
        
        <div>
            <input type="checkbox" id="recordarme" name="recordarme">
            <label for="recordarme" style="font-weight: normal; color: var(--color-texto);">Recordarme en este equipo</label>
        </div>
        
        <button type="submit">Entrar</button>
      </form>
    </section>

<?php endif; ?>

    <section>
      <h2>Búsqueda rápida</h2>
      <form action="resultados.php" method="get">
        <label for="ciudad">Ciudad:</label>
        <input type="text" id="ciudad" name="ciudad">
        <button type="submit">Buscar</button>
      </form>
    </section>

    <section>
      <h2>Últimos anuncios</h2>
      <ul>
        <li>
          <article>
            <a href="aviso.php?id=1">
              <img src="img/casa1.jpg" alt="Foto de vivienda 1" width="100">
              <h3>Piso céntrico en Madrid</h3>
              <p>250.000€</p>
            </a>
          </article>
        </li>
        <li>
          <article>
            <a href="aviso.php?id=5">
              <img src="img/casa5.jpg" alt="Foto de vivienda 5" width="100">
              <h3>Casa rural en Asturias</h3>
              <p>150.000€</p>
            </a>
          </article>
        </li>
      </ul>
    </section>

<?php
require_once 'include/footer.php';
?>