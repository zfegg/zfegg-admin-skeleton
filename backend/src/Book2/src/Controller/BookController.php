<?php

namespace Book2\Controller;

use Book\Entity\Book;
use Book\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Zfegg\PsrMvc\Attribute\FromBody;
use Zfegg\PsrMvc\Attribute\HttpDelete;
use Zfegg\PsrMvc\Attribute\HttpGet;
use Zfegg\PsrMvc\Attribute\HttpPost;
use Zfegg\PsrMvc\Attribute\PrepareResult;
use Zfegg\PsrMvc\Attribute\Route;
use Zfegg\PsrMvc\Attribute\RouteGroup;
use Zfegg\PsrMvc\Exception\NotFoundHttpException;
use Zfegg\PsrMvc\Preparer\SerializationPreparer;

#[RouteGroup('api.book.v2')]
class BookController
{

    use LoggerAwareTrait;

    private EntityManagerInterface $em;
    private SerializerInterface $serializer;

    public function __construct(
        EntityManagerInterface $em,
        Serializer $serializer,
        LoggerInterface $logger
    ) {
        $this->setLogger($logger);
        $this->em = $em;
        $this->serializer = $serializer;
    }

    #[HttpGet('books')]
    public function getList(ServerRequestInterface $request): iterable
    {
        $name = $request->getQueryParams()['name'] ?? null;
        $page = (int) ($request->getQueryParams()['page'] ?? 1);
        $pageSize = min((int) ($request->getQueryParams()['page_size'] ?? 20), 100);

        /** @var BookRepository $repo */
        $repo = $this->em->getRepository(Book::class);
        $paginator = $repo->findAllPaginator($name, $page, $pageSize);

//        $result = $this->serializer->normalize($paginator);

        return [
            'data' => $paginator,
            'total' => $paginator->count(),
        ];
    }

    #[HttpGet('books/{id}')]
    public function get(int $id): ?object
    {
        return $this->em->find(Book::class, $id);
    }

    #[HttpPost(
        'books',
        options: [
            'schema' => 'book:///book.create.json', // 验证POST参数配置
        ]
    )]
    #[PrepareResult(SerializationPreparer::class, ['status' => 201])]
    public function create(#[FromBody(root: true)] array $data): Book
    {
        $book = $this->serializer->denormalize($data, Book::class);
        $this->em->persist($book);
        $this->em->flush();

        return $book;
    }


    #[Route(
        'books/{id}',
        options: [
            'schema' => 'book:///book.update.json', // 验证 update 参数配置
        ],
        methods: ['PUT', 'PATCH']
    )]
    public function update(int $id, #[FromBody(root: true)] array $data): Book
    {
        $book = $this->em->find(Book::class, $id);
        if (! $book) {
            throw new NotFoundHttpException('资源未找到');
        }

        $context[AbstractNormalizer::OBJECT_TO_POPULATE] = $book;
        $this->serializer->denormalize($data, Book::class, null, $context);

        $this->em->flush();

        return $book;
    }

    #[HttpDelete('books/{id}')]
    public function delete(int $id): void
    {
        $book = $this->em->find(Book::class, $id);
        if (! $book) {
            throw new NotFoundHttpException('资源未找到');
        }

        $this->em->remove($book);
        $this->em->flush();
    }

    #[HttpGet('/books/{id}/[action]')]
    public function soldOut(int $id): void
    {
        /** @var Book $book */
        $book = $this->em->find(Book::class, $id);
        $book->setStatus(Book::STATUS_SOLD_OUT);
    }
}
