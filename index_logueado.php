<?php
$titulo_pagina = "PI - Menú Principal (Logueado)";
$menu_tipo = 'privado';

// [MODIFICADO]
// 1. Incluimos head.php (que ya incluye sesion.php)
require_once 'include/head.php'; 

// [MODIFICADO]
// 2. Controlamos que el usuario esté logueado para ver esta página 
controlar_acceso_privado();
?>

    <section style="background-color: #e5efff; border: 1px solid var(--color-primario);">
        
        <h2><?php echo get_saludo(); ?></h2> 
        
        <?php 
        // Mensaje de última visita (Task 1) 
        // Esta variable de sesión solo se crea en sesion.php si el login fue por cookie
        if (isset($_SESSION['ultima_visita'])): 
        ?>
            <p>Tu última visita (registrada por la cookie) fue el <?php echo htmlspecialchars($_SESSION['ultima_visita']); ?>.</p>
        <?php else: ?>
            <p>Te has conectado ahora mismo.</p>
        <?php endif; ?>
        
    </section>

    <section>
      <h2>Búsqueda rápida</h2>
      <form action="resultados.php" method="get"> <label for="ciudad">Ciudad:</label>
        <input type="text" id="ciudad" name="ciudad">
        <button type="submit">Buscar</button>
      </form>
    </section>

    <section>
      <h2>Últimos anuncios</h2>
      <ul>
        <li>
          <article>
            <a href="aviso.php?id=1"> <img src="img/casa1.jpg" alt="Foto de vivienda 1" width="100">
              <h3>Piso céntrico en Madrid</h3>
              <p>250.000€</p>
            </a>
          </article>
        </li>
        <li>
          <article>
            <a href="aviso.php?id=2"> <img src="img/casa2.jpg" alt="Foto de vivienda 2" width="100">
              <h3>Apartamento en Valencia</h3>
              <p>750€/mes</p>
            </a>
          </article>
        </li>
        <li>
          <article>
            <a href="aviso.php?id=5"> <img src="img/casa5.jpg" alt="Foto de vivienda 5" width="100">
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