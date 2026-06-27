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
    | GeniusPay — Passerelle de paiement africaine
    |--------------------------------------------------------------------------
    */
    'geniuspay' => [
        'public_key'     => env('GENIUSPAY_PUBLIC_KEY'),
        'secret_key'     => env('GENIUSPAY_SECRET_KEY'),
        'api_url'        => env('GENIUSPAY_API_URL', 'http://pay.genius.ci/api/v1/merchant'),
        'webhook_secret' => env('GENIUSPAY_WEBHOOK_SECRET'),
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