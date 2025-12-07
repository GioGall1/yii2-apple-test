<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'x90b7-FGzUp5NiXgDQ8sg9Ssf2uNz2E3',
        ],
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => sprintf(
                'mysql:host=%s;dbname=%s',
                getenv('DB_HOST') ?: 'mysql',
                getenv('DB_NAME') ?: 'yii2advanced'
            ),
            'username' => getenv('DB_USER') ?: 'yii2advanced',
            'password' => getenv('DB_PASSWORD') ?: 'secret',
            'charset' => 'utf8',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
    ];
}

return $config;
