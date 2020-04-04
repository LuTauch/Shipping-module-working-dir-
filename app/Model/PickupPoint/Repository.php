<?php


namespace LuTauch\app\Model\Repository;


use Nette\Database\Context;

abstract class Repository
{

    protected $database;

    public function __construct(Context $database)
    {
        $this->database = $database;
    }

    public function findAll()
    {
        return $this->database->table($this->getTableName());
    }

    public function findBy($by)
    {
        return $this->database->table($this->getTableName())->where($by);
    }


    /**
     * Vkládá data do tabulky
     * @param $data
     */
    public function insert($data)
    {
        //$data = Encoding::normalizeUtf8String($data);

        return $this->database->table($this->getTableName())->insert($data);
    }


    /**
     * Implementace insertUpdate do repozitáře
     * @param array $insert
     * @param $onDuplicate
     * @return bool|int|\Nette\Database\Table\ActiveRow
     */
    public function insertUpdate(array $insert, $onDuplicate = NULL)
    {
        if (is_null($onDuplicate)) {
            return $this->insert($insert);
        }

        //$insert = Encoding::normalizeUtf8String($insert);

        return $this->database->query(
            'INSERT INTO ' . $this->getTableName() . ' ? ON DUPLICATE KEY UPDATE ?',
            $insert, $onDuplicate);
    }


    /**
     * Vymaže záznam podle primárního klíče
     * @param $id
     */
    public function delete($id)
    {
        return $this->findBy(array($this->database->table($this->getTableName())->getPrimary() => (int)$id))->delete();
    }

    /*
     * Ulozi nebo updatne zaznam
     */
    public function save($data, $id = 0)
    {
        $id = (int)$id;

        if (0 === $id) {
            $record = $this->insert($data);
        } else {
            $record = $this->findRow($id);

            $record->update($data);
        }

        return $record;
    }

    /**
     * Vrací záznamu podle INT primary key
     * @param $id
     */
    public function findRow($id)
    {
        return $this->database->table($this->getTableName())->get((int)$id);
    }

    public function beginTransaction()
    {
        $this->database->beginTransaction();
    }

    public function commit()
    {
        $this->database->commit();
    }

    public function rollBack()
    {
        $this->database->rollBack();
    }


    protected abstract function getTableName();


}