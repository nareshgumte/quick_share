<?php

return [
    'class' => 'yii\log\Dispatcher',
    'traceLevel' => YII_DEBUG ? 1 : 0,
    'targets' => [
        [
            'class' => 'yii\log\FileTarget',
            'logVars' => [],
            'levels' => ['info'],
            'logFile' => '@app/runtime/logs/info.log',
            'maxFileSize' => 1024 * 2,
            'maxLogFiles' => 10,
        ],
        [
            'class' => 'yii\log\FileTarget',
            'logVars' => [],
            'levels' => ['error'],
            'logFile' => '@app/runtime/logs/error.log',
            'maxFileSize' => 1024 * 2,
            'maxLogFiles' => 10,
        ],
        [
            'class' => 'yii\log\FileTarget',
            'logVars' => [],
            'levels' => ['warning'],
            'logFile' => '@app/runtime/logs/warning.log',
            'maxFileSize' => 1024 * 2,
            'maxLogFiles' => 10
        ],
        [
            'class' => 'yii\log\FileTarget',
            'logVars' => [],
            'levels' => ['trace'],
            'logFile' => '@app/runtime/logs/trace.log',
            'maxFileSize' => 1024 * 2,
            'maxLogFiles' => 10,
        ],
        [
            'class' => 'yii\log\FileTarget',
            'logVars' => [],
            'levels' => ['error'],
            'categories' => ['dbException'],
            'logFile' => '@app/runtime/logs/dbException.log',
            'maxFileSize' => 1024 * 2,
            'maxLogFiles' => 10,
        ],
        [
            'class' => 'yii\log\FileTarget',
            'logVars' => [],
            'levels' => ['info'],
            'categories' => ['requestResponse'],
            'logFile' => '@app/runtime/logs/requestResponse.log',
            'maxFileSize' => 1024 * 2,
            'maxLogFiles' => 10,
        ]
    ],
];