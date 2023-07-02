<?php

namespace BookTest\Controller;

use Book\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Zfegg\Admin\Admin\Test\InitEntityTrait;
use Zfegg\ExpressiveTest\AbstractActionTestCase;

class BookControllerTest extends AbstractActionTestCase
{
    use InitEntityTrait;

    public function testSoldOut(): void
    {
        $this->initSession();

        $em = $this->container->get(EntityManagerInterface::class);

        $book = new Book();
        $book->setName('test' . uniqid());
        $book->setBarcode(1235);
        $em->persist($book);
        $em->flush();

        $this->get("/api/book/books/{$book->getId()}/sold-out")->assertNoContent();

        $em->remove($book);
        $em->flush();
    }
}
