<?php

namespace LuTauch\App\Model\PacketItems;

class Packet
{
    private $ID;
    public $recipient;
    //zatim jen hmotnost
    public $size;
    public $cod;
    private $eshop;
    private $serviceName;
    private $additionalServices;




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
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param mixed $recipient
     */
    public function setRecipient($recipient): void
    {
        $this->recipient = $recipient;
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
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @param mixed $serviceName
     */
    public function setServiceName($serviceName): void
    {
        $this->serviceName = $serviceName;
    }

    /**
     * @return array
     */
    public function getAdditionalServices()
    {
        return $this->additionalServices;
    }

    /**
     * @param array $additionalServices
     */
    public function setAdditionalServices($additionalServices): void
    {
        $this->additionalServices = [
            'eveningDelivery' => $additionalServices['eveningDelivery'],
            'weekendDelivery' => $additionalServices['weekendDelivery'],
            'expressDelivery' => $additionalServices['expressDelivery'],
        ];
    }


}