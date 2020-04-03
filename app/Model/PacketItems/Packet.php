<?php

namespace LuTauch\App\Model\PacketItems;

class Packet
{
    private $ID;
    private $receiver;
    //zatim jen hmotnost
    private $size;
    private $cod;
    private $eshop;
    private $priceOption;
    private $service;



    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @param mixed $ID
     */
    public function setID($ID): void
    {
        $this->ID = $ID;
    }

    /**
     * @return mixed
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param mixed $receiver
     */
    public function setReceiver($receiver): void
    {
        $this->receiver = $receiver;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size): void
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getCod()
    {
        return $this->cod;
    }

    /**
     * @param mixed $cod
     */
    public function setCod($cod): void
    {
        $this->cod = $cod;
    }

    /**
     * @return mixed
     */
    public function getEshop()
    {
        return $this->eshop;
    }

    /**
     * @param mixed $eshop
     */
    public function setEshop($eshop): void
    {
        $this->eshop = $eshop;
    }

    /**
     * @return mixed
     */
    public function getPriceOption()
    {
        return $this->priceOption;
    }

    /**
     * @param mixed $priceOption
     */
    public function setPriceOption($priceOption): void
    {
        $this->priceOption = $priceOption;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service): void
    {
        $this->service = $service;
    }



}