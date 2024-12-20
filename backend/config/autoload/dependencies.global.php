<?php

declare(strict_types=1);

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Zfegg\PsrMvc\Container\SerializationPreparerStackFactory;
use Zfegg\PsrMvc\Preparer\PreparerStack;
use Zfegg\PsrMvc\Routing\RouteMetadata;

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [
            DenormalizerInterface::class => ObjectNormalizer::class,
        ],
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
            // Fully\Qualified\InterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories'  => [
            PreparerStack::class => SerializationPreparerStackFactory::class,
        ],
        'abstract_factories' => [
        ],
        'auto' => [
            'aot' => [
                'namespace' => 'AppAoT\Generated',
                'directory' => __DIR__ . '/../../data/runtime',
                'logger' => Psr\Log\LoggerInterface::class,
            ],
            'types' => [
                RouteMetadata::class => [
                    'parameters' => [
                        'paths' => glob(dirname(__DIR__) . '/../src/*/src/Controller'),
                        'excludePaths' => [
                        ],
                    ],
                ]
            ]
        ],
    ],
];
