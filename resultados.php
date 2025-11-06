<?php
$titulo_pagina = "Resultados de búsqueda - PI";
// Incluye la cabecera y la lógica del gestor de sesión
require_once 'include/head.php';
// Recoge la ciudad buscada de la url
$ciudad_buscada = htmlspecialchars($_GET['ciudad'] ?? 'N/A');
?>

    <h2><span class="icono-estrella"></span>Resultados de búsqueda</h2>
    
    <section style="border: 1px solid #007bff; padding: 15px; background-color: #f0f7ff; margin-bottom: 20px;">
        <h3>Parámetros de Búsqueda Recibidos:</h3>
        <ul>
            <li>Ciudad buscada: <strong><?php echo $ciudad_buscada; ?></strong></li>
            
            <?php 
            // Itera sobre todos los parámetros enviados por el formulario de búsqueda
            foreach ($_GET as $key => $value) {
                // Muestra solo los parámetros que no son 'ciudad' y no están vacíos
                if ($key !== 'ciudad' && trim($value) !== '') {
                    echo "<li>" . ucfirst($key) . ":<strong> " . htmlspecialchars($value) . "</strong></li>";
                }
            }
            ?>
        </ul>
    </section>

    <div>
      <ul>
        <li>
          <a href="aviso.php?id=1"> <img src="img/casa1.jpg" alt="Vivienda 1"><br>
            <div class="resultado-info">
              <h3 class="resultado-titulo">Piso céntrico en Madrid</h3>
              <p class="resultado-precio">250.000€</p>
              <p class="resultado-detalles">
                Fecha: 15/09/2025<br>
                Ciudad: Madrid<br>
                País: España
              </p>
            </div>
          </a>
        </li>
        <li>
          <a href="aviso.php?id=2"> <img src="img/casa2.jpg" alt="Vivienda 2"><br>
            <div class="resultado-info">
              <h3 class="resultado-titulo">Apartamento en Valencia</h3>
              <p class="resultado-precio">750€/mes</p>
              <p class="resultado-detalles">
                Fecha: 10/09/2025<br>
                Ciudad: Valencia<br>
                País: España
              </p>
            </div>
          </a>
        </li>
        <li>
          <a href="aviso.php?id=3"> <img src="img/casa3.jpg" alt="Vivienda 3"><br>
            <div class="resultado-info">
              <h3 class="resultado-titulo">Chalet en Sevilla</h3>
              <p class="resultado-precio">400.000€</p>
              <p class="resultado-detalles">
                Fecha: 20/09/2025<br>
                Ciudad: Sevilla<br>
                País: España
              </p>
            </div>
          </a>
        </li>
      </ul>
    </div>

<?php
require_once 'include/footer.php';
?>