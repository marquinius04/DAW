<?php
$titulo_pagina = "Acceso y Anuncios Recientes";
$body_id = "loginPage"; 
$menu_tipo = 'publico';
require_once 'include/head.php'; 
if (isset($_GET['error'])) {
    // Saneamos el error para mostrarlo de forma segura
    $error = htmlspecialchars($_GET['error']);
    echo "<p style='color: red; border: 1px solid red; padding: 10px; background-color: #ffeaea; margin-top: 15px;'>
            ⛔ Error de acceso: {$error}
          </p>";
}
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
require_once 'include/footer.php';
?>