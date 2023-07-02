<?php


namespace Book2\Controller;

use Book\Entity\Group;
use Book\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Zfegg\PsrMvc\Attribute\FromBody;
use Zfegg\PsrMvc\Attribute\FromQuery;
use Zfegg\PsrMvc\Attribute\HttpDelete;
use Zfegg\PsrMvc\Attribute\HttpGet;
use Zfegg\PsrMvc\Attribute\HttpPost;
use Zfegg\PsrMvc\Attribute\PrepareResult;
use Zfegg\PsrMvc\Attribute\Route;
use Zfegg\PsrMvc\Attribute\RouteGroup;
use Zfegg\PsrMvc\Exception\NotFoundHttpException;
use Zfegg\PsrMvc\Preparer\SerializationPreparer;

#[RouteGroup('api.book.v2')]
class GroupController
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

    #[HttpGet('groups')]
    public function getList(
        #[FromQuery]
        ?string $name = null
    ): iterable {
        $criteria = [];
        if ($name) {
            $criteria['name'] = $name;
        }

        /** @var GroupRepository $repo */
        $repo = $this->em->getRepository(Group::class);
        $data = $repo->findBy($criteria);

        return ['data' => $data];
    }

    #[HttpGet('groups/{id}')]
    public function get(int $id): ?Group
    {
        return $this->em->find(Group::class, $id);
    }

    #[HttpPost(
        'groups',
        options: [
            'schema' => 'book:///group.create.json', // 验证POST参数配置
        ]
    )]
    #[PrepareResult(SerializationPreparer::class, ['status' => 201])]
    public function create(
        #[FromBody(root: true)]
        array $data
    ): Group {
        $book = $this->serializer->denormalize($data, Group::class);
        $this->em->persist($book);
        $this->em->flush();

        return $book;
    }

    #[Route(
        'groups/{id}',
        options: [
            'schema' => 'book:///group.create.json', // 验证 update 参数配置
        ],
        methods: ['PUT', 'PATCH']
    )]
    public function update(
        int $id,
        #[FromBody(root: true)]
        array $data
    ): Group {
        $obj = $this->em->find(Group::class, $id);
        if (! $obj) {
            throw new NotFoundHttpException('资源未找到');
        }

        $context[AbstractNormalizer::OBJECT_TO_POPULATE] = $obj;
        $this->serializer->denormalize($data, Group::class, null, $context);

        $this->em->flush();

        return $obj;
    }

    #[HttpDelete('groups/{id}')]
    public function delete(int $id): void
    {
        /** @var GroupRepository $repo */
        $repo = $this->em->getRepository(Group::class);
        $book = $repo->find($id);
        if (! $book) {
            throw new NotFoundHttpException('资源未找到');
        }

        // 方式1: 使用ORM EntityManager 操作删除
        $this->em->remove($book);
        $this->em->flush();

        // 方式2: 使用DQL/SQL 操作删除
        // $repo->delete($id);
        // $repo->delete2($id);
        // $repo->delete3($id);
    }
}
