<?php

use Monolog\Handler\PsrHandler;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'dependencies' => [
        'factories' => [
            'console.logger'     => function (ContainerInterface $container) {
                $file = __DIR__ . '/../../data/logs/console.log';

                $logger = new Monolog\Logger('console');
                $logger->pushProcessor(new Monolog\Processor\UidProcessor());
                $logger->pushHandler(new Monolog\Handler\StreamHandler($file));
                $logger->pushHandler(
                    new PsrHandler(
                        new ConsoleLogger($container->get(ConsoleOutput::class))
                    )
                );


                return $logger;
            },
            ConsoleOutput::class => InvokableFactory::class,
            Application::class => function (ContainerInterface $container) {
                $commands = $container->get('config')['commands'];
                $app = new Application('app');

                $commandMap = [];
                foreach ($commands as $name => $commandName) {
                    if (is_int($name) && ($defaultName = $commandName::getDefaultName())) {
                        $commandMap[$defaultName] = $commandName;
                    } else {
                        $command = $container->get($commandName);
                        if (is_string($name)) {
                            $command->setName($name);
                        }
                        $app->add($command);
                    }
                }
                $app->setCommandLoader(new ContainerCommandLoader(
                    $container,
                    $commandMap
                ));

                return $app;
            },
        ],
    ],
    'commands'     => [],
];
