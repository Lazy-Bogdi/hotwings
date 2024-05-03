<?php

namespace App\Service\CustomManager;

use Doctrine\ODM\MongoDB\DocumentManager;
use App\Interface\DataManager\DataManagerInterface;

class NoSqlDataManager implements DataManagerInterface
{
    private $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
        // dump($this->documentManager->getClient());
    }

    public function save($document)
    {
        $this->documentManager->persist($document);
        $this->documentManager->flush();
    }

    public function remove($document)
    {
        $this->documentManager->remove($document);
        $this->documentManager->flush();
    }


    public function exists($document): bool
    {
        $repo = $this->documentManager->getRepository(get_class($document));
        $result = $repo->findOneBy(['title' => $document->getTitle()]);
        return $result !== null;
    }
}
