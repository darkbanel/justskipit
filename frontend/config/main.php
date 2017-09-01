<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'enableStrictParsing' => true,
            //'suffix' => '.html',
            'rules' => [
                // site routes
                '' => 'site/index',
                'about' => 'site/about',
                'contact' => 'site/contact',
                'logout' => 'site/logout',
                'login' => 'site/login',
                'signup' => 'site/signup',
                'request-password-reset' => 'site/request-password-reset',
                'reset-password' => 'site/reset-password',
                'site/language' => 'site/language',
                // profiles routes
                'profile' => 'profiles/my-profile',
                'user/edit-profile' => 'profiles/update',
                'edit-profile' => 'profiles/update',
                'user/<any>' => 'profiles/index',
                // action routes
                'add-post' => 'actions/create',
                'delete-post' => 'actions/delete',
                'edit-post' => 'actions/edit',
                // movies routes
                'movies' => 'movies/index',
                'movies/index' => 'movies/index',
                'movies/view' => 'movies/view',
                // chat routes
                'chat' => 'chat/index',
                'chat/index' => 'chat/index',
                // comments routes
                'add-comment' => 'comments/create',
                'delete-comment' => 'comments/delete',
                'edit-comment' => 'comments/edit',
                // profile shortURL
                '<any>' => 'profiles/find-by-username',
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
        'social' => [
            // the module class
            'class' => 'kartik\social\Module',

            // the global settings for the Disqus widget
            'disqus' => [
                'settings' => ['shortname' => 'DISQUS_SHORTNAME'] // default settings
            ],

            // the global settings for the Facebook plugins widget
            'facebook' => [
                'appId' => '167738380465356',
                'secret' => '3a78e2e24a99339e837cb3c6ffed5547',
            ],

            // the global settings for the Google+ Plugins widget
            'google' => [
                'clientId' => 'GOOGLE_API_CLIENT_ID',
                'pageId' => 'GOOGLE_PLUS_PAGE_ID',
                'profileId' => 'GOOGLE_PLUS_PROFILE_ID',
            ],

            // the global settings for the Google Analytics plugin widget
            'googleAnalytics' => [
                'id' => 'TRACKING_ID',
                'domain' => 'TRACKING_DOMAIN',
            ],

            // the global settings for the Twitter plugin widget
            'twitter' => [
                'screenName' => 'darkbanel'
            ],

            // the global settings for the GitHub plugin widget
            'github' => [
                'settings' => ['user' => 'GITHUB_USER', 'repo' => 'GITHUB_REPO']
            ],
        ],
        // your other modules
    ]
];
