<?php

namespace App\Factory;

class ModelFactory
{
    private $dbType; // 'sql' or 'nosql'
    private $namespaceMap;

    public function __construct(string $dbType)
    {
        $this->dbType = $dbType;
        $this->namespaceMap = [
            'sql' => 'App\\Entity\\',
            'nosql' => 'App\\Document\\'
        ];
    }

    public function create(string $modelName): object
    {
        $className = ucfirst($modelName); // Ensure first letter is uppercase

        if ($this->dbType === 'nosql') {
            $className .= 'Mongo';
        }

        $fullClassName = $this->namespaceMap[$this->dbType] . $className;
        // dump($fullClassName);
        if (!class_exists($fullClassName)) {

            throw new \InvalidArgumentException("Class '$fullClassName' does not exist.");
        }

        return new $fullClassName();
    }
}
