<?php


namespace Book\Repository;

use Book\Entity\Group;
use Doctrine\ORM\EntityRepository;

class GroupRepository extends EntityRepository
{
    public function rename(int $id, string $newName): void
    {
        $query = $this->_em->createQuery(sprintf('UPDATE %s o SET o.name=?0 WHERE o=?1', Group::class));
        $query->execute([$newName, $id]);
    }

    public function rename2(int $id, string $newName): void
    {
        $query = $this->_em->getConnection()->prepare('UPDATE book_groups SET name=? WHERE id=?');
        $query->executeStatement([$newName, $id]);
    }

    /**
     * 使用 DQL 删除
     */
    public function delete(int $id): void
    {
        $query = $this->_em->createQuery(sprintf("DELETE FROM %s o WHERE o=?0", Group::class));
        $query->execute([$id]);
    }

    /**
     * 使用 SQL 删除
     */
    public function delete2(int $id): void
    {
        $query = $this->_em->getConnection()->prepare(
            sprintf("DELETE FROM %s WHERE id=?0", 'book_groups')
        );
        $query->executeStatement([$id]);
    }
}
