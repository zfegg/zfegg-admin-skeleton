<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;
use Psr\Container\ContainerInterface;

// Load configuration
$config = require __DIR__ . '/config.php';

$dependencies                       = $config['dependencies'];
$dependencies['services']['config'] = $config;

// Build container
$container = new ServiceManager($dependencies);
$container->setService(ContainerInterface::class, $container);

return $container;
