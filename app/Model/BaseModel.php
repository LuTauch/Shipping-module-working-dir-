<?php

namespace LuTauch\App\Model;

use Nette\Database\Context;

/**
 * Class BaseModel provides with methods for a simplified access to the database (work with it).
 * @package App\Model
 */
abstract class BaseModel
{
    /**
     * Database variable
     * @var Context
     */
    protected $database;

    /**
     * BaseModel constructor.
     * @param Context $database
     */
    public function __construct(Context $database)
    {
        $this->database = $database;
    }

    public function findAll()
    {
        return $this->database->table($this->getTableName());
    }

    /**
     * Defines a general sql query 'SELECT FROM table WHERE by'
     * @param array $by condition for the query
     * @return \Nette\Database\Table\Selection
     */
    public function findBy(array $by)
    {
        return $this->database->table($this->getTableName())->where($by);
    }


    /**
     * Gets table name
     * @return mixed
     */
    public abstract function getTableName();
}