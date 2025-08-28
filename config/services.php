<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 🌐 Сторонние сервисы (Third Party Services)
    |--------------------------------------------------------------------------
    |
    | Здесь хранятся учётные данные и настройки для интеграции с внешними сервисами:
    | Mailgun, Postmark, AWS, Slack и другие.
    | Используется для единого центра конфигурации.
    |
    */

    // ✉️ Postmark — сервис отправки почты
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),  // API токен Postmark
    ],

    // ☁️ Amazon SES — облачный почтовый сервис AWS
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),       // Ключ доступа AWS
        'secret' => env('AWS_SECRET_ACCESS_KEY'),// Секретный ключ
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'), // Регион по умолчанию
    ],

    // 📨 Resend — современный сервис отправки почты от Vercel
    'resend' => [
        'key' => env('RESEND_KEY'), // API ключ Resend
    ],

    // 💬 Slack — интеграция для отправки уведомлений в Slack
    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),  // OAuth токен бота
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),           // Канал по умолчанию для уведомлений
        ],
    ],

];
