<?php


namespace LuTauch\App\Model;


class Options
{
    private $weight = 0;
    private $ids = [];
    private $service = '';

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

    /**
     * @return string
     */
    public function getService(): string
    {
        return $this->service;
    }

    /**
     * @param string $service
     */
    public function setService(string $service): void
    {
        $this->service = $service;
    }


}