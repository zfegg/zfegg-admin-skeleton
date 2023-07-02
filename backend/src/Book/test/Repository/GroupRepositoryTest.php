<?php

namespace BookTest\Repository;

use Book\Entity\Group;
use Book\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Zfegg\ExpressiveTest\AbstractActionTestCase;

class GroupRepositoryTest extends AbstractActionTestCase
{

    public function testUpdateDelete(): void
    {
        $em = $this->container->get(EntityManagerInterface::class);

        /** @var GroupRepository $repo */
        $repo = $em->getRepository(Group::class);

        $group = new Group();
        $group->setName('test' . uniqid());
        $em->persist($group);
        $em->flush();

        $repo->rename($group->getId(), 'test-' . uniqid());
        $repo->delete($group->getId());

        $rs = $repo->find(33);

        $this->assertNull($rs);
    }
}
