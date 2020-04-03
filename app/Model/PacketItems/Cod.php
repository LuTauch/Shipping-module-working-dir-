<?php

namespace LuTauch\App\Model\PacketItems;

class Cod
{

    private $currency;
    /**
     * @var string value of cash on delivery
     */
    private $value;

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency): void
    {
        $this->currency = $currency;
    }


    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }


    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

}