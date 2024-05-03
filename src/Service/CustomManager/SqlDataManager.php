<?php

namespace App\Service\CustomManager;

// use App\Service\CustomManager\DataManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Interface\DataManager\DataManagerInterface;

class SqlDataManager implements DataManagerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save($entity)
    {
        // dump($entity);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function remove($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function exists($entity): bool
    {
        $repo = $this->entityManager->getRepository(get_class($entity));
        $result = $repo->findOneBy(['title' => $entity->getTitle()]);
        return $result !== null;
    }
}
