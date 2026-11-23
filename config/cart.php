<?php

return [

    'tax' => 10,

    'database' => [
        'connection' => null,
        'table' => 'shoppingcart',
    ],

    'destroy_on_logout' => false,

    'format' => [
        'decimals' => 0,
        'decimal_point' => ',',
        'thousand_separator' => '.',
    ],

    'calculator' => Gloudemans\Shoppingcart\Calculation\DefaultCalculator::class,

];