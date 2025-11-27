<?php
$titulo_pagina = "Solicitar folleto - PI";
$body_id = "folletoPage"; 
require_once 'include/head.php'; 
require_once 'include/db_connect.php';
require_once 'include/select_options.inc.php'; // Asegúrate de tener este include
controlar_acceso_privado(); 
$mysqli = conectar_bd();

// Obtener ID usuario logueado
$uid = $_SESSION['id_usuario'];

// Obtener solo LOS ANUNCIOS DEL USUARIO 
$sql_ads = "SELECT IdAnuncio, Titulo FROM anuncios WHERE Usuario = $uid";
$res_ads = $mysqli->query($sql_ads);

// Variables para repoblar en caso de error
$val_nombre = htmlspecialchars($_GET['nombre'] ?? '');
$val_email = htmlspecialchars($_GET['email'] ?? '');
?>

    <h2>Solicitar folleto publicitario</h2>
    <p>Complete el formulario para recibir un folleto impreso de uno de sus anuncios.</p>

    <form action="respuesta_folleto.php" method="post" style="max-width: 600px;">
        
        <fieldset style="display: flex; flex-direction: column;">
            <legend>Datos del Anuncio</legend>
            <label for="anuncio">Seleccione su anuncio (*):</label>
            <select id="anuncio" name="anuncio" required>
                <option value="">-- Seleccione --</option>
                <?php while($ad = $res_ads->fetch_assoc()): ?>
                    <option value="<?= $ad['IdAnuncio'] ?>">
                        <?= htmlspecialchars($ad['Titulo']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <span id="anuncioError" class="error"></span>
            
            <label for="texto_adicional">Texto adicional:</label>
            <textarea id="texto_adicional" name="texto_adicional" rows="3" placeholder="Información extra para el folleto..."></textarea>
        </fieldset>

        <fieldset style="display: flex; flex-direction: column;">
            <legend>Datos de Envío</legend>
            
            <label for="nombre">Nombre completo (*):</label>
            <input type="text" id="nombre" name="nombre" value="<?= $val_nombre ?>" required>

            <label for="email">Email de contacto (*):</label>
            <input type="email" id="email" name="email" value="<?= $val_email ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono">

            <label for="calle">Calle (*):</label>
            <input type="text" id="calle" name="calle" required>

            <div style="display: flex; gap: 10px;">
                <div style="flex: 1;">
                    <label for="numero">Número (*):</label>
                    <input type="text" id="numero" name="numero" required>
                </div>
                <div style="flex: 1;">
                    <label for="codigo_postal">CP (*):</label>
                    <input type="text" id="codigo_postal" name="codigo_postal" maxlength="5" required>
                </div>
            </div>

            <label for="localidad">Localidad (*):</label>
            <input type="text" id="localidad" name="localidad" required>

            <label for="provincia">Provincia (*):</label>
            <input type="text" id="provincia" name="provincia" required>
        </fieldset>

        <fieldset style="display: flex; flex-direction: column;">
            <legend>Opciones de Impresión</legend>
            
            <label for="color_portada">Color de portada (Hexadecimal):</label>
            <input type="color" id="color_portada" name="color_portada" value="#004aad">

            <label for="num_copias">Número de copias (1-99):</label>
            <input type="number" id="num_copias" name="num_copias" value="1" min="1" max="99" required>

            <label>Resolución:</label>
            <select name="resolucion" id="resolucion">
                <option value="150">150 DPI (Calidad media)</option>
                <option value="300">300 DPI (Alta calidad)</option>
            </select>

            <p>Tipo de impresión:</p>
            <label style="font-weight: normal;"><input type="radio" name="impresion_color" value="blanco_negro" checked> Blanco y Negro</label>
            <label style="font-weight: normal;"><input type="radio" name="impresion_color" value="color"> Color</label>
            
            <br>
            <label style="font-weight: normal;"><input type="checkbox" name="impresion_precio" value="con_precio" checked> Incluir precio en portada</label>
        </fieldset>

        <button type="submit">Calcular coste y Solicitar</button>
        <button type="button" id="toggleTablaCostes">Mostrar tabla de costes</button>
    </form>
    
    <table id="tablaCostesGenerada" style="display: none; margin: 20px auto;">
        <thead>
            <tr><th>Concepto</th><th>B/N</th><th>Color</th></tr>
        </thead>
        <tbody>
            <tr><td>150 DPI</td><td>5 €</td><td>7 €</td></tr>
            <tr><td>300 DPI</td><td>8 €</td><td>12 €</td></tr>
            <tr><td>Coste fijo envío</td><td colspan="2">2 €</td></tr>
        </tbody>
    </table>

<?php
$mysqli->close();
require_once 'include/footer.php';
?>