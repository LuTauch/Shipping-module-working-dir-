<?php


namespace LuTauch\App\Model;


use LuTauch\App\Model\Factory\CarrierFactory;
use LuTauch\App\Model\PacketItems\Cod;
use LuTauch\App\Model\PacketItems\Packet;
use LuTauch\App\Model\PacketItems\Recipient;
use Nette\Utils\Strings;


class OrderAcceptance
{
    private $carrierName;

    private $serviceName;

    private $pickupPoint;

    private $carrier;

    private $carrierFactory;

    private $packet;

    private $cod;

    private $recipient;

    private $eshop;
    private $currency;
    private $countryCode;


    //tady proběhne úplně celý zpracování objednávky

    public function __construct($eshop, $countryCode, $currency, CarrierFactory $carrierFactory, Packet $packet, Recipient $recipient, Cod $cod)
    {
        $this->eshop = $eshop;
        $this->countryCode = $countryCode;
        $this->currency = $currency;
        $this->carrierFactory = $carrierFactory;
        $this->packet = $packet;
        $this->recipient = $recipient;
        $this->cod = $cod;
    }

    /**
     * @param array $recipient
     * @param array $orderData
     * @param array $additionalServices
     * @param array $serviceData
     * @return bool order processed successfully
     */
    public function acceptOrderFromEshop(array $recipient, array $orderData, array $additionalServices, array $serviceData, $location)
    {
        $this->setRecipientData($recipient);
        $this->setCodData($orderData);
        $this->setPacketData($this->recipient, $this->cod, $serviceData, $orderData, $additionalServices);
        $this->processServiceData($serviceData);

        try {
            $this->carrier = $this->carrierFactory->createCarrier($this->carrierName);
        } catch (\NonExistingCarrierException $e) {
        }

        return $this->carrier->processOrder($this->serviceName, $this->packet, $location);
    }

    public function processServiceData($serviceData): void
    {
        $res = Strings::split($serviceData['serviceName'], '~ - \s*~');
        $this->carrierName = $res[0];
        $this->serviceName = $res[1];
    }



    public function setRecipientData(array $recipientData): void
    {
        $this->recipient->setName($recipientData['name']);
        $this->recipient->setSurname($recipientData['surname']);
        $this->recipient->setPhoneNo($recipientData['phone']);
        $this->recipient->setEmail($recipientData['email']);
        $this->recipient->setStreet($recipientData['street']);
        $this->recipient->setHouseNo($recipientData['nr']);
        $this->recipient->setCity($recipientData['city']);
        $this->recipient->setCityPart($recipientData['cityPart']);
        $this->recipient->setZipCode($recipientData['zip']);
        $this->recipient->setCountryCode($this->countryCode);
    }

    public function setCodData($orderData): void
    {
        $this->cod->setValue($orderData['codValue']);
        $this->cod->setCurrency($this->currency);
    }

    public function setPacketData(Recipient $recipient, Cod $cod, array $serviceData, array $orderData, array $additionalServices)
    {
        $this->packet->setID($orderData['orderId']);
        $this->packet->setCod($cod);
        $this->packet->setRecipient($recipient);
        $this->packet->setSize($orderData['size']);
        $this->packet->setAdditionalServices($additionalServices);
        $this->packet->setPickupPoint($serviceData['pickupPoint']);
    }
}