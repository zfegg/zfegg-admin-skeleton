<?php

namespace BookTest\Repository;

use Book\Entity\Book;
use Book\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Zfegg\ExpressiveTest\AbstractActionTestCase;

class BookRepositoryTest extends AbstractActionTestCase
{

    public function testFetchPaginator(): void
    {
        $em = $this->container->get(EntityManagerInterface::class);

        /** @var BookRepository $repo */
        $repo = $em->getRepository(Book::class);

        $book = new Book();
        $book->setName('test' . uniqid());
        $book->setBarcode(1235);
        $em->persist($book);
        $em->flush();

        $result = $repo->findAllPaginator();
        $data = iterator_to_array($result);
        $this->assertGreaterThanOrEqual(1, count($data));

        $result = $repo->findAllPaginator2();
        $data = iterator_to_array($result);
        $this->assertGreaterThanOrEqual(1, count($data));
        $this->assertGreaterThanOrEqual(1, $result->count());

        $data = $repo->findAllPaginator3();
        $this->assertGreaterThanOrEqual(1, count($data));

        $em->remove($book);
        $em->flush();
    }
}
