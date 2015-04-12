<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
       'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module'
        ],
        'blog' => [
            'class' => 'app\modules\blog\Blog',
        ],
        'forum'=>[
            'class'=>'app\modules\yboard\YBoard',
        ],
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu',
            'navbar'=>[
                ['label'=>Yii::t('app', 'Forum Admin'), 'url'=>['/forum/setting/index']],
            ],
            'as access'=>[
                'class'=>'mdm\admin\components\AccessControl'
            ],
        ], 
        'profile' => [
            'class' => 'hosanna\profile\ProfileModule',
            'profiles'=>[
                ['Forum Profile', '/forum/member/view','id'],
            ]            
        ],
        'backup' => [
            'class' => 'spanjeta\modules\backup\Module',
        ],
    ],
    'components' => [
        'image'=>[
            'class' => 'yii\image\ImageDriver',
            'driver' => 'GD',  //GD or Imagick
        ],
        'authManager' => [
            'class' =>'yii\rbac\DbManager' // or use 'yii\rbac\PhpManager', 
        ],
        'urlManager' => [ 
            'enablePrettyUrl' => true,
            'showScriptName' => false,             
            'enableStrictParsing' => false,
            'rules' => [  
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller:\w+>/<action:\w+>/<id:\d+>',
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'uoCUi7LRm11RoeCIN2FD4vdg801myQ2p',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'hosanna\profile\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/profile/account/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ], 
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.hosannahighertech.co.tz',
                'username' => 'noreply@hosannahighertech.co.tz',
                'password' => 'Upendo123#',
                'port' => '26',
                'encryption' => '',
            ],
        ],
        'urlManager' => [ 
            'enablePrettyUrl' => true,
            'showScriptName' => false,             
            'enableStrictParsing' => false,
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
        'formatter' => [ 
            'class' => 'yii\i18n\Formatter', 
            'dateFormat' => 'd-M-Y', 
            'datetimeFormat' => 'd-M-Y H:i:s', 
            'timeFormat' => 'H:i:s', 
        ], 
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
    
]; 

return $config;
