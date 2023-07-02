<?php


namespace Book\Repository;

use Book\Entity\Book;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BookRepository extends EntityRepository
{

    /**
     * DQL 查询构建器
     */
    public function findAllPaginator(?string $name = null, int $page = 1, int $pageSize = 20): Paginator
    {
        $query = $this->createQueryBuilder('o');

        if ($name) {
            $query->where(
                $query->expr()->eq('o.name', ':name')
            );
            $query->setParameter('name', $name);
        }

        $query->setFirstResult(($page - 1) * $pageSize);
        $query->setMaxResults($pageSize);

        return new Paginator($query);
    }

    /**
     * DQL 查询语句
     */
    public function findAllPaginator2(?string $name = null, int $page = 1, int $pageSize = 20): Paginator
    {
        $dql = sprintf("SELECT o FROM %s o ", Book::class);
        $params = [];
        if ($name) {
            $dql .= ($name ? 'WHERE o.name=:name' : '');
            $params = ['name' => $name];
        }
        $query = $this->_em->createQuery($dql);
        $query->setParameters($params);
        $query->setFirstResult(($page - 1) * $pageSize);
        $query->setMaxResults($pageSize);

        return new Paginator($query);
    }

    /**
     * 原生 SQL 查询语句
     */
    public function findAllPaginator3(?string $name = null, int $page = 1, int $pageSize = 20): array
    {
        $sql = "SELECT * FROM books";
        $params = [];
        if ($name) {
            $sql .= ($name ? 'WHERE name=:name' : '');
            $params = ['name' => $name];
        }
        $sql .= " LIMIT $page, $pageSize";

        $rsm = new ResultSetMappingBuilder($this->_em);
        $rsm->addRootEntityFromClassMetadata($this->_entityName, 'books');

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameters($params);

        return $query->getResult();
    }
}
