<?php

use Zfegg\AdminCenterOauthHandler\Handler\AuthenticatedHandler\AuthenticatedHandlerInterface;
use Zfegg\AdminCenterOauthHandler\Handler\AuthenticatedHandler\ForceRegister;
use Zfegg\AdminCenterOauthHandler\Mezzio\ConfigProvider;
use Zfegg\AdminCenterOauthHandler\Service\AdminCenterService;

return [
    'dependencies' => [
        'factories' => [
            'db.yc_admin_center' => \App\Factory\DoctrineToPdoFactory::class,
        ],
        'aliases' => [
            AuthenticatedHandlerInterface::class => ForceRegister::class,
        ],
    ],
    ConfigProvider::CONFIG_KEY => [
        AdminCenterService::class => [
            'api' => 'https://admincenter-dev.zfegg.com',
            'app_id' => 38,
            'app_uid' => 'test_key_001',
            'app_secret' => 'a8a3828fc98fa909ae5d7f0baf113a3618ffeb34',
        ],
    ],

    'middleware_pipeline' => [
        [
            'path' => '/zfegg/admin-center-auth',
            'middleware' => [
                \Mezzio\Session\SessionMiddleware::class,
            ],
            'priority' => 1000,
        ],
    ],
];
