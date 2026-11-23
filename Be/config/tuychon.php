<?php

return [
    'sizes' => [
        'S' => ['ma' => 'S', 'ten' => 'Size Nhỏ (S)', 'gia' => 0],
        'M' => ['ma' => 'M', 'ten' => 'Size Vừa (M)', 'gia' => 5000],
        'L' => ['ma' => 'L', 'ten' => 'Size Lớn (L)', 'gia' => 10000],
    ],
    'da' => [
        '100%'  => ['ma' => '100%', 'ten' => '100% Đá (Bình thường)', 'gia' => 0],
        '70%'   => ['ma' => '70%', 'ten' => '70% Đá', 'gia' => 0],
        '50%'   => ['ma' => '50%', 'ten' => '50% Đá (Ít đá)', 'gia' => 0],
        '0%'    => ['ma' => '0%', 'ten' => 'Không đá', 'gia' => 0],
        'NONG'  => ['ma' => 'NONG', 'ten' => 'Uống nóng', 'gia' => 0],
    ],
    'duong' => [
        '100%'  => ['ma' => '100%', 'ten' => '100% Đường (Chuẩn)', 'gia' => 0],
        '70%'   => ['ma' => '70%', 'ten' => '70% Đường', 'gia' => 0],
        '50%'   => ['ma' => '50%', 'ten' => '50% Đường (Ít ngọt)', 'gia' => 0],
        '30%'   => ['ma' => '30%', 'ten' => '30% Đường', 'gia' => 0],
        '0%'    => ['ma' => '0%', 'ten' => 'Không đường', 'gia' => 0],
    ],
    'toppings' => [
        'tran_chau_den' => [
            'id'    => 'tran_chau_den',
            'ten'   => 'Trân châu đen',
            'gia'   => 5000,
        ],
        'tran_chau_trang' => [
            'id'    => 'tran_chau_trang',
            'ten'   => 'Trân châu trắng',
            'gia'   => 5000,
        ],
        'thach_ca_phe' => [
            'id'    => 'thach_ca_phe',
            'ten'   => 'Thạch cà phê',
            'gia'   => 5000,
        ],
        'kem_cheese' => [
            'id'    => 'kem_cheese',
            'ten'   => 'Kem Cheese béo',
            'gia'   => 10000,
        ],
    ]
];
