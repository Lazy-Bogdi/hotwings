<?php


namespace App\Interface\DataManager;

interface   DataManagerInterface
{
    public function save($model);
    public function remove($model);
    public function exists($model): bool;
}
