<?php

$config = [
    'id' => 'quick-share',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'app\modules\v1\Module',
        ]
    ],
    'components' => [
        'request' => require(__DIR__ . '/_request.php'),
        'user' => require(__DIR__ . '/_user.php'),
        'response' => require(__DIR__ . '/_response.php'),
        'log' => require(__DIR__ . '/_log.php'),
        'urlManager' => require(__DIR__ . '/_urlRules.php'),
        'db' => require(__DIR__ . '/_db.php'),
    ],
    'params' => require(__DIR__ . '/_params.php'),
];

if (YII_ENV_DEV) {
// configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*'] // adjust this to your needs
    ];
}

return $config;
