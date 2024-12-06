<?php


namespace Book2;

use Mezzio\Authentication\AuthenticationMiddleware;
use Mezzio\Session\SessionMiddleware;
use Zfegg\Admin\Admin\Middleware\AuthorizationMiddleware;
use Zfegg\ContentValidation\ContentValidationMiddleware;
use Zfegg\PsrMvc\Routing\Group;
use Zfegg\PsrMvc\Routing\RouteMetadata;

class ConfigProvider
{

    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
//            'doctrine' => $this->getDoctrine(),
            'routes' => $this->getRoutes(),
        ];
    }

    private function getRoutes(): array
    {
        $group = new Group('/api/book/v2', [
            SessionMiddleware::class,
            AuthenticationMiddleware::class,    // 登录认证中间件(登录验证)
            AuthorizationMiddleware::class,     // 授权验证
            ContentValidationMiddleware::class, // 数据内容验证支持
        ], 'api.book.v2.');

//        $group->get('/books/{id}/sold-out', BookController::class . '@soldOut');
//        $group->get('/books', BookController::class . '@getList');
//        $group->get('/books/{id}', BookController::class . '@get');
//        $group->post('/books',
//            BookController::class . '@create',
//            'books:create',
//            [
//                'schema' => 'book:///book.create.json', // 验证POST参数配置
//            ]
//        );
//        $group->route(
//            '/books/{id}',
//            BookController::class . '@update',
//            ['PUT', 'PATCH'],
//            'books:update',
//            [
//                'schema' => 'book:///book.update.json', // 验证 update 参数配置
//            ]
//        );
//        $group->delete('/books/{id}', BookController::class . '@delete');

//        $group->get('/groups', GroupController::class . '@getList');
//        $group->get('/groups/{id}', GroupController::class . '@get');
//        $group->post(
//            '/groups',
//            GroupController::class . '@create',
//            'group:create',
//            [
//                'schema' => 'book:///group.create.json', // 验证POST参数配置
//            ]
//        );
//        $group->route(
//            '/groups/{id}',
//            GroupController::class . '@update',
//            ['PUT', 'PATCH'],
//            'group:update',
//            [
//                'schema' => 'book:///group.create.json', // 验证 update 参数配置
//            ]
//        );
//        $group->delete('/groups/{id}', GroupController::class . '@delete');

        // $rest = Route::restRoute($group);
        // $rest('groups',);

        return $group->getRoutes();
    }

    public function getDoctrine(): array
    {
        return [
            'driver' => [
                'annotation' => [
                    'paths' => [
                        __DIR__ . '/Entity',
                    ],
                ],
            ],
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
            ],
            'auto' => [
                'types' => [
                    RouteMetadata::class => [
                        'parameters' => [
                            'groups' => [
                                'api.book.v2' => [
                                    'prefix' => '/api/book/v2/',
                                    'middlewares' => [
                                        SessionMiddleware::class,
                                        AuthenticationMiddleware::class,    // 登录认证中间件(登录验证)
                                        AuthorizationMiddleware::class,     // 授权验证
                                        ContentValidationMiddleware::class, // 数据内容验证支持
                                    ],
                                    'name' => 'api.book.v2.'
                                ]
                            ],
                        ],
                    ]
                ]
            ],
        ];
    }
}
