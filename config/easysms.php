<?php

return [
    'timeout' => 5.0,

    'default' => [
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,
        'gateways' => [
            'yunpian'
        ]
    ],
    'gateways' => [
        'errorlog' => [
            'file' => '/tmp/easy-sms.log'
        ],
        'yunpian' => [
            'api_key' => env('YUNPIN_API_KEY')
        ]
    ]
];
