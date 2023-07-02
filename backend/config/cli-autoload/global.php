<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\Output\ConsoleOutput;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'dependencies' => [
        'factories' => [
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
    'commands'     => [
//        SFMessenger\Command\ConsumeMessagesCommand::class,
//        SFMessenger\Command\StopWorkersCommand::class,
//        SFMessenger\Command\SetupTransportsCommand::class,
//        SFMessenger\Command\FailedMessagesRemoveCommand::class,
//        SFMessenger\Command\FailedMessagesRetryCommand::class,
//        SFMessenger\Command\FailedMessagesShowCommand::class,
    ],
];
