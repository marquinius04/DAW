<?php
// Define las variables para la plantilla
$titulo_pagina = "PI - Menú Principal (Logueado)";
$menu_tipo = 'privado';
// Nota: No hay ID específico en el body original, pero puedes dejarlo vacío o añadir uno si lo necesitas.
require_once 'include/head.php'; 
?>

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