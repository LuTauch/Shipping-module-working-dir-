<?php


namespace LuTauch\App\Model;


class Options
{
    private $weight = 0;
    private $ids = [];

    /**
     * @return mixed
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }

    public function getIds(): array
    {
        return $this->ids;
    }

    public function setIds(array $ids) : void
    {
        $this->ids = $ids;
    }



}