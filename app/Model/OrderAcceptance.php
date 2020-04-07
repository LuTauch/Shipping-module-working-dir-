<?php


namespace LuTauch\App\Model;


use Latte\Strict;
use LuTauch\App\Model\Factory\CarrierFactory;
use LuTauch\App\Model\PacketItems\Cod;
use LuTauch\App\Model\PacketItems\Packet;
use LuTauch\App\Model\PacketItems\Receiver;
use LuTauch\App\Model\PacketItems\Size;
use Nette\Utils\Strings;
use Tracy\Debugger;


class OrderAcceptance
{
    private $carrierName;

    private $serviceName;

    private $carrier;

    private $carrierFactory;

    private $packet;

    private $cod;

    private $receiver;

    private $counter;

    private $eshop;
    private $currency;
    private $countryCode;


    //tady proběhne úplně celý zpracování objednávky

    public function __construct($eshop, $countryCode, $currency, CarrierFactory $carrierFactory, Packet $packet, Receiver $receiver, Cod $cod)
    {
        $this->eshop = $eshop;
        $this->countryCode = $countryCode;
        $this->currency = $currency;
        $this->carrierFactory = $carrierFactory;
        $this->packet = $packet;
        $this->receiver = $receiver;
        $this->cod = $cod;
        $this->counter = 0;

    }

    /**
     * @param array $receiverData
     * @param string $weight
     * @param string $codValue
     * @param string $name
     * @return bool order processed successfully
     */
    public function acceptOrderFromEshop(array $receiverData, string $weight, string $codValue, string $name)
    {
        $this->counter++;
        $this->setReceiver($receiverData);
        $this->setCod($codValue);
        $this->setPacket($this->counter, $this->receiver, $this->cod, $weight);
        $this->processServiceName($name);

        try {
            $this->carrier = $this->carrierFactory->createCarrier($this->carrierName);
        } catch (\NonExistingCarrierException $e) {
        }

        return $this->carrier->processOrder($this->serviceName, $this->packet);
    }

    public function processServiceName($name): void
    {
        $res = Strings::split($name, '~ - \s*~');
        $this->carrierName = $res[0];
        $this->serviceName = $res[1];
    }

    public function setReceiver(array $receiverData): void
    {
        $this->receiver->setName($receiverData['name']);
        $this->receiver->setSurname($receiverData['surname']);
        $this->receiver->setPhoneNo($receiverData['phone']);
        $this->receiver->setEmail($receiverData['email']);
        $this->receiver->setStreet($receiverData['street']);
        $this->receiver->setHouseNo($receiverData['nr']);
        $this->receiver->setCity($receiverData['city']);
        $this->receiver->setZipCode($receiverData['zip']);
        $this->receiver->setCountryCode($this->countryCode);
    }

    public function setCod($codValue): void
    {
        $this->cod->setValue($codValue);
        $this->cod->setCurrency($this->currency);
    }

    public function setPacket(int $id, Receiver $receiver, Cod $cod, string $weight)
    {
        $this->packet->setCod($cod);
        $this->packet->setReceiver($receiver);
        $this->packet->setSize($weight);
        $this->packet->setID($id);
    }
}