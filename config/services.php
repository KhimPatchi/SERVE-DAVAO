<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY', '6LfkqSYsAAAAAN5bhGKAyIwZoG1LC6arj2PGzS6D'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY', '6LfkqSYsAAAAACqSOpAa9yy4z6teLEVbisPqGGHG'),
    ],

    'idanalyzer' => [
        'api_key' => env('ID_ANALYZER_API_KEY'),
        'region' => env('ID_ANALYZER_REGION', 'US'),
        'verify_face' => env('ID_ANALYZER_VERIFY_FACE', true),
        'accepted_documents' => env('ID_ANALYZER_ACCEPTED_DOCUMENTS', 'passport,driverlicense,nationalid'),
        'confidence_threshold' => env('ID_ANALYZER_CONFIDENCE_THRESHOLD', 0.5),
        'profile_id' => env('ID_ANALYZER_PROFILE_ID'),
    ],

    'mapbox' => [
        'token' => env('MAPBOX_PUBLIC_TOKEN', ''),
    ],

];
