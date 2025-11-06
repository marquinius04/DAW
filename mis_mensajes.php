<?php
$titulo_pagina = "Mis mensajes - PI";
// Incluye la cabecera y el gestor de sesión
require_once 'include/head.php'; 
// Controla que solo usuarios logueados puedan acceder a la página
controlar_acceso_privado(); 
?>

    <h2>Mis mensajes</h2>
    <p>Ejemplo de mensajes enviados y recibidos:</p>
    
    <table>
      <thead>
        <tr>
          <th>Tipo de mensaje</th>
          <th>Texto del mensaje</th>
          <th>Fecha</th>
          <th>Usuario emisor/receptor</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>Solicitar cita</td><td>Hola, ¿puedo visitar el piso este sábado?</td><td>2025-09-30</td><td>De: usuario1</td></tr>
        <tr><td>Más información</td><td>Quisiera recibir más detalles sobre el piso.</td><td>2025-09-29</td><td>Para: usuario2</td></tr>
        <tr><td>Comunicar oferta</td><td>Ofrezco 120.000€ por el piso.</td><td>2025-09-28</td><td>De: usuario3</td></tr>
      </tbody>
    </table>

<?php
require_once 'include/footer.php';
?>