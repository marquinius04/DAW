<?php
// Almacena los últimos anuncios visitados en $ultimos_anuncios
$ultimos_anuncios = get_ultimos_anuncios();

if (!empty($ultimos_anuncios)):
?>
    <section class="caja-lateral" style="background-color: #fff8e1; border: 1px solid #ffc107; margin-top: 2.5em;">
        <h3><span class="icono">history</span> Últimos anuncios visitados</h3>
        
        <ul style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1em; list-style: none; padding: 0;">
            
            <?php 
            // Recorre el array para mostrar los anuncios del más reciente al más antiguo
            foreach (array_reverse($ultimos_anuncios) as $anuncio): 
            ?>
                <li style="border: 1px solid #ddd; border-radius: 6px; padding: 10px; background: #fff;">
                    <a href="aviso.php?id=<?php echo htmlspecialchars($anuncio['id']); ?>" style="text-decoration: none; color: inherit;">
                        <img src="<?php echo htmlspecialchars($anuncio['foto']); ?>" alt="Miniatura" style="width: 100%; height: 100px; object-fit: cover; border-radius: 4px;">
                        <h4 style="color: var(--color-primario); margin: 5px 0 0 0; font-size: 0.9em;">
                            <?php echo htmlspecialchars($anuncio['titulo']); ?>
                        </h4>
                        <p style="font-size: 0.8em; margin: 3px 0; color: #333; font-weight: bold;">
                            <?php echo htmlspecialchars($anuncio['precio']); ?>
                        </p>
                        <p style="font-size: 0.8em; margin: 3px 0; color: #555;">
                            <?php echo htmlspecialchars($anuncio['ciudad']); ?>, <?php echo htmlspecialchars($anuncio['pais']); ?>
                        </p>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php 
endif; 
?>

</main> <footer>
    <p><a href="accesibilidad.php">Declaración de accesibilidad</a></p>
    <p>&copy; 2025 PI - Pisos & Inmuebles. Todos los derechos reservados.</p>
    <p>Autores: Marcos Díaz Moleón y Gustavo Joel Paladines Dávila</p>
  </footer>
</body>
</html>