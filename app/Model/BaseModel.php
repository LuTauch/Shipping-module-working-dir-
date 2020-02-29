<?php

namespace App\Model;

use Nette\Database\Context;

abstract class BaseModel
{
    protected $database;

    public function __construct(Context $database)
    {
        $this->database = $database;
    }

    /**
     * @param array $by
     * @return \Nette\Database\Table\Selection
     */
    public function findBy(array $by)
    {
        return $this->database->table($this->getTableName())->where($by);
    }

    public abstract function getTableName();
}