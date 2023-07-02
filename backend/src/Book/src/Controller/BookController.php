<?php


namespace Book\Controller;

use Book\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Authentication\AuthenticationMiddleware;
use Mezzio\Session\SessionMiddleware;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Zfegg\Admin\Admin\Middleware\AuthorizationMiddleware;
use Zfegg\ContentValidation\ContentValidationMiddleware;
use Zfegg\PsrMvc\Attribute\HttpGet;
use Zfegg\PsrMvc\Attribute\Route;

#[Route(
    '/api/book',
    [
        SessionMiddleware::class,
        AuthenticationMiddleware::class,    // 登录认证中间件(登录验证)
        AuthorizationMiddleware::class,     // 授权验证
        ContentValidationMiddleware::class, // 数据内容验证支持
    ],
    'api.book.'
)
]
class BookController
{
    use LoggerAwareTrait;

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->setLogger($logger);
        $this->em = $em;
    }

    #[HttpGet('books/{id}/[action]')]
    public function soldOut(int $id): void
    {
        $em = $this->em;
        /** @var Book $book */
        $book = $this->em->find(Book::class, $id);
        $book->setStatus(Book::STATUS_SOLD_OUT);
        $em->persist($book);
        $em->flush();
    }
}
