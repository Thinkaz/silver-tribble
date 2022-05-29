<?php

return [
    'version_id' => '3d2f829e-43cf-41a6-b4b1-57a68d80f48d',
    'version' => '1.8.2',

    'licensee' => '76561198399027236',

    'general' => [
        'show_copyright' => true,
        'show_credits' => true,
    ],

    'reactions' => [
        [
            'emoji' => 'img/reactions/check.svg',
            'display' => 'Agree'
        ],
        [
            'emoji' => 'img/reactions/cross.svg',
            'display' => 'Disagree'
        ],
        [
            'emoji' => 'img/reactions/funny.svg',
            'display' => 'Funny'
        ],
        [
            'emoji' => 'img/reactions/confused.svg',
            'display' => 'Confused'
        ],
        [
            'emoji' => 'img/reactions/medal.svg',
            'display' => 'Winner'
        ],
        [
            'emoji' => 'img/reactions/heart.svg',
            'display' => 'Friendly'
        ],
        [
            'emoji' => 'img/reactions/pow.svg',
            'display' => 'Zing'
        ]
    ],

    'actions' => [
        'usergroup' => [
            'name' => 'Usergroup',
            'component' => 'actions.usergroup',
            'fields' => [
                'group' => ['required'],
                'expire_group' => ['required_without:permanent|nullable']
            ],
        ],
        'darkrp_money' => [
            'name' => 'DarkRP Money',
            'component' => 'actions.darkrp-money',
            'fields' => [
                'amount' => ['required', 'integer']
            ]
        ],
        'console_command' => [
            'name' => 'Console Command',
            'component' => 'actions.console-command',
            'fields' => [
                'cmd' => ['required'],
                'expire_cmd' => ['nullable']
            ]
        ],
        'weapons' => [
            'name' => '(Permanent) Weapons',
            'component' => 'actions.weapons',
            'fields' => [
                'classes' => ['array'],
                'perm' => ['nullable', 'boolean']
            ]
        ],
        'custom_lua' => [
            'name' => 'Custom LUA',
            'component' => 'actions.custom-lua',
            'fields' => [
                'on_bought' => ['required'],
                'on_expired' => ['nullable']
            ]
        ],
        'darkrp_level' => [
            'name' => 'DarkRP Level',
            'component' => 'actions.darkrp-level',
            'fields' => [
                'amount' => ['required', 'integer'],
            ],
        ],
        'ps_points' => [
            'name' => 'Pointshop Points',
            'component' => 'actions.ps-points',
            'fields' => [
                'amount' => ['required', 'integer'],
            ],
        ],
        'ps2_standard_points' => [
            'name' => 'Pointshop 2 Standard Points',
            'component' => 'actions.ps2-standard-points',
            'fields' => [
                'amount' => ['required', 'integer'],
            ],
        ],
        'ps2_premium_points' => [
            'name' => 'Pointshop 2 Premium Points',
            'component' => 'actions.ps2-premium-points',
            'fields' => [
                'amount' => ['required', 'integer'],
            ],
        ],
        'pulsar' => [
            'name' => 'Pulsar Credits',
            'component' => 'actions.pulsar',
            'fields' => [
                'amount' => ['required', 'integer'],
            ],
        ],
    ],

    'currencies' => [
        'EUR' => '€',
        'USD' => '$',
        'GBP' => '£',
        'CAD' => 'C$',
        'AUD' => 'A$',
        'BRL' => 'R$'
    ],

    'payment_gateways' => [
        'paypal' => \App\PaymentGateways\PayPal\Gateway::class,
        'stripe' => \App\PaymentGateways\Stripe\Gateway::class,
        'coinbase' => \App\PaymentGateways\Coinbase\Gateway::class
    ],

    'stripe_payment_methods' => [
        'acss_debit',
        'afterpay_clearpay',
        'alipay',
        'bacs_debit',
        'bancontact',
        'boleto',
        'card',
        'eps',
        'fpx',
        'giropay',
        'grabpay',
        'ideal',
        'oxxo',
        'p24',
        'sepa_debit',
        'sofort',
        'wechat_pay'
    ],

    'games' => [
        'gmod' => [
            'type' => new App\GameTypes\Source(4000),
            'display' => 'Garry\'s Mod',
        ],

        'rust' => [
            'type' => new App\GameTypes\Source(252490),
            'display' => 'Rust',
        ],

        'fivem' => [
            'type' => \App\GameTypes\FiveM::class,
            'display' => 'FiveM',
        ],

        'csgo' => [
            'type' => new App\GameTypes\Source(730),
            'display' => 'CSGO',
        ],
    ],

    'analytics_enabled' => (bool) env('ANALYTICS_ENABLED', true),
];
