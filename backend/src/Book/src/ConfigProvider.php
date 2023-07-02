<?php


namespace Book;

use Mezzio\Authentication\AuthenticationMiddleware;
use Mezzio\Session\SessionMiddleware;
use Opis\JsonSchema\Validator;
use Zfegg\Admin\Admin\Middleware\AuthorizationMiddleware;
use Zfegg\ApiRestfulHandler\Utils\Route;
use Zfegg\ContentValidation\ContentValidationMiddleware;
use Zfegg\PsrMvc\Routing\Group;
use Zfegg\PsrMvc\Routing\RouteMetadata;

class ConfigProvider
{

    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'doctrine' => $this->getDoctrine(),
            'routes' => $this->getRoutes(),
            'rest' => [
                // 键名可与路由名称匹配
                'api.book.books' => [
                    'resource' => 'Book\\BookResource',
                    'serialize_context' => [

                        // 配置API要返回的字段, 不配置默认返回全部字段
                        'attributes' => [
                            'id',
                            'name',
                            'barcode',
                            'createdAt',
                            'status',
                            'group' => [
                                "id",
                                "name",
                            ],
                        ]
                    ]
                ],
                'api.book.groups' => [
                    'resource' => 'Book\\GroupResource',
                ],
                'api.book.groups.books' => [
                    'resource' => 'Book\\Group\\BookResource',
                ],
            ],
            'doctrine.orm-resources' => [
                'Book\\BookResource' => [
                    'entity' => Entity\Book::class,
                    'extensions' => [
                        // 接口分页支持
                        'pagination' => [
                        ],
                        // 接口 kendoUI组件 query规则支持
                        'kendo_query_filter' => [
                            'fields' => [
                                'name' => [
                                    'op' => ['eq', 'startswith']
                                ],
                                'barcode' => [
                                    'op' => ['eq']
                                ],
                                'status' => [
                                    'op' => ['eq', 'in']
                                ],
                                'created_at' => [
                                    'op' => ['gte', 'lte']
                                ],
                            ]
                        ],
                        // 接口排序支持
                        'sort' => [
                            'fields' => [
                                'id' => 'desc'
                            ]
                        ],
                    ],
                ],
                'Book\\GroupResource' => [
                    'entity' => Entity\Group::class,
                ],
                'Book\\Group\\BookResource' => [
                    'entity' => Entity\Book::class,
                    'extensions' => [
                    ],
                ],
            ],

            Validator::class => [
                'resolvers' => [
                    'protocolDir' => [
                        ['book', '', __DIR__ . '/../schema']
                    ]
                ]
            ],
            RouteMetadata::class => [
                'paths' => [
                    __DIR__ . '/Controller',
                ]
            ]
        ];
    }


    private function getRoutes(): array
    {
        $group = new Group('/api/book', [
            SessionMiddleware::class,
            AuthenticationMiddleware::class,    // 登录认证中间件(登录验证)
            AuthorizationMiddleware::class,     // 授权验证
            ContentValidationMiddleware::class, // 数据内容验证支持
        ], 'api.book.');

//        $group->get('/books/{id}/sold-out', Controller\BookController::class . '@soldOut');

        $rest = Route::restRoute($group);
        $rest(
            'books',
            [
                'schema:POST' => 'book:///book.create.json',
                'schema:PATCH' => 'book:///book.update.json',
            ],
        );
        $rest(
            'groups',
            [
                'schema:POST' => 'book:///group.create.json',
                'schema:PATCH' => 'book:///group.create.json',
            ],
        );

        return $group->getRoutes();
    }

    public function getDoctrine(): array
    {
        return [
            'driver' => [
                'attribute' => [
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
            ]
        ];
    }
}
