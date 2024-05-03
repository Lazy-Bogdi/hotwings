<?php

namespace App\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Service\CustomManager\SqlDataManager;
use App\Service\CustomManager\NoSqlDataManager;
use App\Interface\DataManager\DataManagerInterface;

class DataManagerFactory
{
    public static function create(EntityManagerInterface $entityManager, DocumentManager $documentManager, string $dbType): DataManagerInterface
    {
        if ($dbType === 'nosql') {
            return new NoSqlDataManager($documentManager);
        } else {
            return new SqlDataManager($entityManager);
        }
    }
}
