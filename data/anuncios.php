<?php
// Almacena dos anuncios ficticios, uno para las ID impares y otro para las ID pares

$anuncios_ficticios = [
    // Datos del anuncio para IDs impares (por ejemplo, ID = 1, 3, 5)
    'impar' => [
        'titulo' => 'Chalet de lujo con piscina',
        'precio' => '750.000 €',
        'ciudad' => 'Marbella, España',
        'fecha' => '20/10/2025',
        'texto' => 'Espectacular chalet ubicado en zona premium. Cuenta con 4 habitaciones, 3 baños, jardín y piscina privada. Vistas a la montaña y al mar.',
        'caracteristicas' => [
            'Dormitorios' => 4,
            'Baños' => 3,
            'Superficie' => '250 m²',
            'Garaje' => 'Sí'
        ],
        'fotos' => [
            'img/casa1.jpg',
            'img/casa1_1.jpg',
            'img/casa1_2.jpg'
        ]
    ],
    
    // Datos del anuncio para IDs pares (por ejemplo, ID = 2, 4, 6)
    'par' => [
        'titulo' => 'Estudio funcional y económico',
        'precio' => '450 €/mes',
        'ciudad' => 'Bilbao, España',
        'fecha' => '15/09/2025',
        'texto' => 'Acogedor estudio cerca del centro. Ideal para estudiantes o solteros. Cocina americana, baño completo y todos los gastos incluidos en el precio.',
        'caracteristicas' => [
            'Dormitorios' => 1,
            'Baños' => 1,
            'Superficie' => '40 m²',
            'Mobiliario' => 'Incluido'
        ],
        'fotos' => [
            'img/casa2.jpg'
        ]
    ]
];
?>