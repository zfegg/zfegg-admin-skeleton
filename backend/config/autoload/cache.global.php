<?php

return [
    'dependencies' => [
        'factories' => [
            'cache.default' => \Zfegg\Psr11SymfonyCache\CacheFactory::class,
            'cache.array' => [\Zfegg\Psr11SymfonyCache\CacheFactory::class, 'array'],
        ],
        'aliases' => [
            \Psr\Cache\CacheItemPoolInterface::class => 'cache.default',
        ],
    ],

    'cache' => [
        // At the bare minimum you must include a default adaptor.
        'default' => [
            'type' => 'filesystem',
            'options' => [
                'directory' => 'data/cache/'
            ],
        ],

        // Some other Adaptor.  Keys are the names for each adaptor
        'array' => [
            'type' => 'array',
        ],
    ],

];
