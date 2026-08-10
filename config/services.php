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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'rekognition' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'bucket'    => env('AWS_BUCKET'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'ap-southeast-1'),
        'collection_id' => env('REKOGNITION_COLLECTION_ID', 'dost9-users'),
        'participant_id' => env('REKOGNITION_PARTICIPANT_ID', 'dost9-participants'),
        'testparticipant_id' => env('REKOGNITION_TESTPARTICIPANT_ID', 'dost9-testparticipants'),
        'vip_id' => env('REKOGNITION_VIP_ID', 'dost9-vip'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    // Self-hosted coturn on the same box as Reverb (see VipController::
    // turnCredentials()) - lets the Scanner.vue <-> FaceRecognitionPage.jsx
    // WebRTC video connect even when STUN alone can't punch through both
    // sides' NATs. 'secret' must match coturn's static-auth-secret exactly.
    'turn' => [
        'secret' => env('TURN_SECRET'),
        'host' => env('TURN_HOST', 'one.dost9.ph'),
        'port' => env('TURN_PORT', 3478),
        'ttl' => env('TURN_TTL', 3600),
    ],

];
