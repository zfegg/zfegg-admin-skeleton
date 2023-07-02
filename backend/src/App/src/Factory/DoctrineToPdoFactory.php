<?php

namespace App\Factory;

use Psr\Container\ContainerInterface;

class DoctrineToPdoFactory
{

    public function __invoke(ContainerInterface $container): \PDO
    {
        /** @var \Doctrine\DBAL\Connection $conn */
        $conn = $container->get('doctrine.connection.default');

        return $conn->getNativeConnection();
    }
}
