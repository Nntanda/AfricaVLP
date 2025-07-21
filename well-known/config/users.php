<?php
return [
    'Users' => [
        'Email' => [
            'validate' => true
        ],
        'Registration' => [
            'active' => true,
            'allowLoggedIn' => false
        ],
        'Token' => [
            'expiration' => '+1 day'
        ]
    ]
];
