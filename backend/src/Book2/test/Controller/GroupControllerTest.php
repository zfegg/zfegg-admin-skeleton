<?php

namespace BookTest2\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Zfegg\Admin\Admin\Test\InitEntityTrait;
use Zfegg\ApiRestfulHandler\Test\RestfulApiTestTrait;
use Zfegg\ExpressiveTest\AbstractActionTestCase;

class GroupControllerTest extends AbstractActionTestCase
{
    use InitEntityTrait;
    use RestfulApiTestTrait;

    private string $path = '/api/book/v2/groups';

    public function testCurd(): void
    {
        $this->initSession();

        $conn = $this->container->get(EntityManagerInterface::class)->getConnection();
        $conn->delete('book_groups', ['name' => 'test']);
        $data = [
            'name' => 'test',
        ];
        $this->apiCurd(
            $data,
            ['name' => 'test2'],
            ['name' => 'test'],
        );
    }
}
