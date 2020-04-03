<?php

namespace LuTauch\App\Model\PacketItems;

class Size
{
    /** @var string $weight of packet (in kg) */
    private $weight;

    //DPD v metrech, Zasilkovna v mm
    private $length;
    private $width;
    private $height;

    public function __construct($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function getWeight(): string
    {
        return $this->weight;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $length
     */
    public function setLength($length): void
    {
        $this->length = $length;
    }

    /**
     * @param mixed $width
     */
    public function setWidth($width): void
    {
        $this->width = $width;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height): void
    {
        $this->height = $height;
    }

    public function setWeight($weight): void {
       $this->weight = $weight;
    }


}