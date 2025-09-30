<?php

use Laravel\Fortify\Features;

return [
    'features' => [
        Features::registration(),      // 会員登録
        Features::resetPasswords(),
    ],
    'home' => '/',
    'guard'     => 'web',
    'passwords' => 'users',

    'limiters'  => [
        'login' => null,
    ],

    'views' => true,
];
