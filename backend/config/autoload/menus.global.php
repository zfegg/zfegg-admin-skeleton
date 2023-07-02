<?php

return [
    'menus' => [
        'book' => [
            'name' => '书本管理',
            'children' => [
                'pro-table' => [
                    'name' => 'ProTable',
                    'permissions' => [
                        'book.books:GET',
                        'book.books:POST',
                        'book.books:PUT',
                        'book.books:PATCH',
                        'book.books:DELETE',
                    ],
                ],
                'table' => [
                    'name' => 'Table',
                    'permissions' => [
                        'book.books:GET',
                    ],
                    'children' => [
                        [
                            'name' => '查询',
                            'permissions' => [
                                'book.books:GET',
                                'book.books2:GET',
                            ]
                        ],
                        [
                            'name' => '编辑',
                            'permissions' => [
                                'book.books:POST',
                                'book.books:PUT',
                                'book.books:PATCH',
                            ]
                        ],
                        [
                            'name' => '删除',
                            'permissions' => [
                                'book.books:DELETE',
                            ]
                        ],
                    ]
                ],
            ]
        ]
    ]
];
