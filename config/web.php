<?php

$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'assetManager' => [
            'appendTimestamp' => false, //change to true for production
            'converter'=>[
                'class'=> 'nizsheanez\assetConverter\Converter',
                'force'=> true, // true : If you want convert your sass each time without time dependency
                'destinationDir' => 'css', //at which folder of @webroot put compiled files
                'parsers' => [
                    'scss' => [ // file extension to parse
                        'class' => 'nizsheanez\assetConverter\Scss',
                        'output' => 'css', // parsed output file type
                        'options' => [ // optional options
                            'enableCompass' => true, // default is true
                            'importPaths' => ['@app/assets/scss'], // import paths, you may use path alias here, 
                                // e.g., `['@path/to/dir', '@path/to/dir1', ...]`
                            'outputStyle' => 'compressed', // May be `compressed`, `crunched`, `expanded` or `nested`,
                            // see more at http://sass-lang.com/documentation/file.SASS_REFERENCE.html#output_style
                        ],
                    ],
                ],
            ],
            'bundles' => [
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        'js/bootstrap.min.js',
                    ]
                ],
                //use the bootstrap css file
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        'css/bootstrap.min.css',
                    ]
                ],
                //cancel the jquery file, my own link loads faster and is a smaller file!
                'yii\web\JqueryAsset' => [
                    'js' => []
                ],
            ],
        ],
        /* 'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ], */
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'GI4G9buPlXXy7UtWlYOYLK3LxkrB4Sh7',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'team' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\Team',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp-relay.sendinblue.com',
                'port' => '587',
                'username' => 'jef@webcaststudio.be',
                'password' => 'QDnG73EzOILvZJrx',
                'encryption' => 'tls',
            ],
            'useFileTransport' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                            
                'home' => 'site/home',
                'over-ons' => 'site/overons',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'teamlogout' => 'site/teamlogout',
                'signup' => 'site/signup',
                'signupteam' => 'site/signupteam',
                'loginteam' => 'site/loginteam',
                'contact' => 'site/contact',
                'end' => 'site/end',
                'mailsignup' => 'site/mailsignup',
                'mailconfirmation' => 'site/mailconfirmation',           
                
                'user/<action:(index|create|update|edit|delete|members|resetpassword|assess)>' => 'user/<action>',
                'user/<id>' => 'user/view',

                'quiz/<action:(index|create|update|delete)>' => 'quizModule/quiz/<action>',
                'quiz/<slug>' => 'quizModule/quiz/view',

                'round/<action:(index|create|delete|update|moveitem|start|solutions|startquiz|viewquestion|viewsolution|previousround|form)>' => 'quizModule/round/<action>',
                'round/<slug>' => 'quizModule/round/view',

                'question/<action:(index|create|delete|update|view|moveitem)>' => 'quizModule/question/<action>',
                'question/<id>' => 'quizModule/question/view',

                'quiz-event/<action:(index|create|delete|update|view)>' => 'quizModule/quiz-event/<action>',
                'quiz-event/<id>' => 'quizModule/quiz-event/view',

                'team/<action:(index|create|delete|update)>' => 'team/<action>',
                'team/<id>' => 'team/view',

                'profile/<action:(index|create|delete|update)>' => 'profile/<action>',
                'profile/<slug>' => 'profile/view',

                'mailcontact/<action:(index|create|delete|update)>' => 'mailcontactModule/mailcontact/<action>',
                'mailcontact/<slug>' => 'mailcontactModule/mailcontact/view',

                'slider/<action:(index|create|delete|update|singleview|duoview|quadview|adminview)>' => 'sliderModule/slider/<action>',
                'slide/<action:(index|create|delete|update|view|moveslideup|moveslidedown|moveslidefirst|moveslidelast)>' => 'sliderModule/slide/<action>',

            ],
        ],
    ],
    'params' => [
        'adminEmail' => 'jef@webcaststudio.be',
        'contactEmail' => 'jef@webcaststudio.be',
        'sitename' => 'quizapp',
        'image-extension' => '-quizapp.jpg',
    ],
    'modules' => [
        'quizModule' => [
            'class' => 'app\modules\quizModule\quizModule',
        ],
        'sliderModule' => [
            'class' => 'app\modules\sliderModule\SliderModule',
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['components']['assetManager']['forceCopy'] = true;
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::8080'],
    ];
}

return $config;
