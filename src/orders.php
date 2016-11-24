<?php


use mhndev\order\entities\common\Order;

return [

    '*' => [
        Order::INIT.':::'.Order::DELIVERED => 'order which just initiated cant be marked ass delivered',
    ],

    'system' => [

    ],

    'driver' => [

    ],

    'customer' => [

    ]

];
