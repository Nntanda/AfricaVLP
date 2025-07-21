<?php

/**
 * Users config
 */
return ['Users' => [
    'table' => 'users',
    'Registration' => [
        'active' => true
    ],
    'Email' => [
        'validate' => true,
    ],
    'Token' => [
        'expiration' => 604800
    ],
    'Profile' => [
        // 'route' => ['plugin' => false, 'controller' => 'Users', 'action' => 'profile'],
        'route' => '/',
    ],
    'Avatar' => [
        'placeholder' => 'avatar_placeholder.png'
    ],
    'Key' => [
        'Session' => [
            // session key to store the social auth data
            'social' => 'Users.social',
            // userId key used in reset password workflow
            'resetPasswordUserId' => 'Users.resetPasswordUserId',
        ],
        // form key to store the social auth data
        'Form' => [
            'social' => 'social'
        ],
        'Data' => [
            // data key to store the users email
            'email' => 'email',
            // data key to store email coming from social networks
            'socialEmail' => 'info.email',
            // data key to check if the remember me option is enabled
            'rememberMe' => 'remember_me',
        ],
    ],
]];