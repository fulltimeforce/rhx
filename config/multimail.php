<?php

return [
    /*
    |--------------------------------------------------------------------------
    | List your email providers
    |--------------------------------------------------------------------------
    |
    | Enjoy a life with multimail
    |
    */
    'use_default_mail_facade_in_tests' => true,

    'emails'  => [
        'ceo@fulltimeforce.com' => [
            'pass'          => 'gxrkvqzekryjlhxu',
            'username'      => 'ceo@fulltimeforce.com',
            'from_name'     => 'Kevin Campo',
        ],
        'luisana.moncada@fulltimeforce.com' => [
            'pass'          => 'bxijnhurtytozmmu',
            'username'      => 'luisana.moncada@fulltimeforce.com',
            'from_name'     => 'Luisana Moncada',
        ],
        'andrea.flores@fulltimeforce.com'  => [
            'pass'          => 'hmqxgrefldppsmkc',
            'username'      => 'andrea.flores@fulltimeforce.com',
            'from_name'     => 'Andrea Flores',
        ],
        'analucia.valdez@fulltimeforce.com'=>[
            'pass'          => 'hmqxgrefldppsmkc',
            'username'      => 'analucia.valdez@fulltimeforce.com',
            'from_name'     => 'Ana Lucia Valdez',
        ],
        'gabriela.alvarez@fulltimeforce.com' => [
            'pass'          => 'actfobhuiwxizfbt',
            'username'      => 'gabriela.alvarez@fulltimeforce.com',
            'from_name'     => 'Gabriela Alvarez',
        ],
    ],

    'provider' => [
        'default' => [
            'host'      => env('MAIL_HOST'),
            'port'      => env('MAIL_PORT'),
            'encryption' => env('MAIL_ENCRYPTION'),
            'stream' => [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]
        ],
    ],

    

];
