<?php
return [
    'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,
    'rules' => [
        ['class' => 'yii\rest\UrlRule', 'controller' => ['v1/contact'], 'pluralize' => false, 'extraPatterns' =>
            [
                'add' => 'add',
                'sync-contacts' => 'sync-contacts',
                'friends'   =>  'friends',
                'profile'   => 'profile',
                'visit'     => 'visit',
                'update-status' =>  'update-status',
                'save-files'    =>  'save-files',
                'file-list'     =>  'file-list'
            ]
        ],
        
    ]
];
