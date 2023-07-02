<?php

namespace BookTest\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Zfegg\Admin\Admin\Test\InitEntityTrait;
use Zfegg\ApiRestfulHandler\Test\RestfulApiTestTrait;
use Zfegg\ExpressiveTest\AbstractActionTestCase;

class BookTest extends AbstractActionTestCase
{
    use InitEntityTrait;
    use RestfulApiTestTrait;

    private string $path = '/api/book/books';

    public function testCurd(): void
    {
        $this->initSession();

        $conn = $this->container->get(EntityManagerInterface::class)->getConnection();

        $conn->delete('book_groups', ['name' => 'testt']);
        $conn->insert('book_groups', ['name' => 'testt']);
        $lastGroupId = $conn->lastInsertId();

        $conn->delete('books', ['name' => 'test']);

        $data = [
            'name' => 'test',
            'barcode' => 1002,
            'status' => 1,
            'group_id' => $lastGroupId,
        ];

        $this->apiCurd(
            $data,
            ['barcode' => 1003],
            ['barcode' => 1004],
        );
        $conn->delete('book_groups', ['id' => $lastGroupId]);
    }
}
