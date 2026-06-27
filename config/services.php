<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Moneroo — Passerelle de paiement africaine (PawaPay)
    |--------------------------------------------------------------------------
    */
    'moneroo' => [
        'public_key'     => env('MONEROO_PUBLIC_KEY'),
        'secret_key'     => env('MONEROO_SECRET_KEY'),
        'api_url'        => env('MONEROO_API_URL', 'https://api.moneroo.io/v1'),
        'webhook_url'    => env('MONEROO_WEBHOOK_URL'),
        'webhook_secret' => env('MONEROO_WEBHOOK_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Anthropic Claude API — Génération de pages de vente IA
    |--------------------------------------------------------------------------
    */
    'anthropic' => [
        'api_key' => env('ANTHROPIC_API_KEY'),
    ],

];