<?php
// Fichero: datos/usuarios.php
// Almacena los usuarios permitidos (Usuario => Contraseña)
// Usamos hash para las contraseñas para mayor seguridad, aunque la práctica no lo exige explícitamente.
// Para simplificar, usaremos texto plano, ya que las comprobaciones de la práctica son básicas.

$usuarios_permitidos = [
    'a' => 'a',
    'user1' => 'clave1',
    'admin' => 'secreto',
    'marcos' => 'a',
    'gustavo' => 'a'
];

?>