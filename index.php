<?php
// 1. Definir variables para head.php
$titulo_pagina = "Acceso y Anuncios Recientes";
$body_id = "loginPage"; // Mantenemos el ID de la página para JS si lo necesita

// 2. Incluir la cabecera y el inicio del cuerpo
require_once 'include/head.php'; 
?>

<section>
      <h2>Acceso de usuario</h2>
      <form action="respuesta_login.php" method="post">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" >
        <span id="usuarioError" class="error"></span>
        <label for="clave">Contraseña:</label>
        <input type="password" id="clave" name="clave" >
        <span id="claveError" class="error"></span> <button type="submit">Entrar</button>
      </form>
    </section>

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
// 4. Incluir el pie de página y el cierre de etiquetas
require_once 'include/footer.php';
?>