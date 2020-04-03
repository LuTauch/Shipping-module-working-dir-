<?php

namespace LuTauch\App\Model\PacketItems;

class Cod
{
    /**
     * @var string type of currency
     */
    private $currency;

    /**
     * @var string value of cash on delivery
     */
    private $value;

    /** Czech crown */
    const CURRENCY = "CZK";


    public function __construct($value)
    {
        $this->value =  $value;
        $this->currency = self::CURRENCY;

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