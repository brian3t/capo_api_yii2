<?php

$db     = [
    'class' => 'apaoww\oci8\Oci8DbConnection',
    'dsn' => 'oci8:dbname=(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=52.8.1.171)(PORT=1521))(CONNECT_DATA=(SID=ORCL)));charset=AL32UTF8;', // Oracle
    'username' => 'carpoolnowdb',
    'password' => 'Duip34jitjit-',
    'attributes' => [
        // PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::ATTR_CASE => PDO::CASE_LOWER,
    ]
];
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'name' => 'TimeTracker',
    // Need to get one level up:
    'basePath' => dirname(dirname(__DIR__)),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // Enable JSON Input:
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    // Create API log in the standard log dir
                    // But in file 'api.log':
                    'logFile' => '@app/runtime/logs/api.log',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/cuser'],
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                ],
            ]
        ],
        'db' => $db,
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],

    ],
    'modules' => [
        'v1' => [
            'class' => 'app\api\modules\v1\Module',
            'basePath' => '@app/api/modules/v1',
        ],
    ],
    'params' => $params,
];

return $config;
