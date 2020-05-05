<?php

namespace LuTauch\App\Model\Carrier;

use http\Exception\InvalidArgumentException;
use LuTauch\App\Model\ICarrier;
use LuTauch\App\Model\PacketItems\Packet;
use LuTauch\App\Model\PacketItems\Sender;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use Tracy\Debugger;
use Tracy\ILogger;

class CeskaPosta implements ICarrier
{

    /**
     * @var Sender
     */
    private $sender;

    /**
     * path to local file
     */
    const WEEKEND_DELIVERY = '18+19';
    const EVENING_DELIVERY = '1B';

    public function __construct(Sender $sender)
    {
        $this->sender = $sender;
    }

    public function processOrder(string $service, Packet $packet, string $location)
    {
        $code = $this->getServiceCode($service);
        $senderData = $this->sender->getSenderData();
        $packetData = $this->getPacketData($service, $packet, $code);
        $res = $this->exportData($location, $senderData, $packetData);

        if (!$res) {
            Debugger::log('Nepodařilo se zpracovat objednávku.', ILogger::ERROR);
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Gets all the parcel data for direct delivery service together in csv format.
     * @param string $service
     * @param Packet $packet
     * @param string $code
     * @return string
     */
    /* Format is: 'id packet;receiver name;zip code;city;city part;street;-;house nr;country code-CZ;
    phone;email;weight(kg);additional.services;price;cod price;
    vs cod;vs packet=idpacketu;-;-;packet id of the main packet;packet number;
    number of packets;-;-;-;
    */
    //TODO: vyresit doplnkove sluzby a udanou cenu
    public function getPacketData(string $service, Packet $packet, string $code) : string {
        $newId = $this->generateParcelIdNumber($service, $packet);
        $data = [];
        $data[0] = $newId;
        $data[1] = $packet->recipient->getSurname() . $packet->recipient->getName();
        $data[2] = $this->getAddress($packet, $code);
        $data[8] = $packet->recipient->getCountryCode();
        $data[9] = $packet->recipient->getPhoneNo();
        $data[10] = $packet->recipient->getEmail();
        $data[11] = $packet->getSize();
        $data[12] = 'doplnkove-sluzby';
        $data[13] = '';
        $data[14] = $this->getCod($packet);
        $data[15] = '';
        $data[16] = '';
        $data[17] = '';
        $data[17] = '';
        $data[18] = '\n';

        return implode(';', $data);
    }

    /**
     * Gets address part of the final data string.
     * @param Packet $packet
     * @param string $code
     * @return string
     */
    private function getAddress(Packet $packet, string $code) {
        $address = [];
        $pickupPoint = $this->splitPickupPointName($packet->getPickupPoint());
        if ($code == 'DR') {
            $address[0] = $packet->recipient->getStreet();
            $address[1] = '';
            $address[2] = $packet->recipient->getHouseNo();
            $address[3] = $packet->recipient->getZipCode();
            $address[4] = $packet->recipient->getCity();
            $address[5] = $packet->recipient->getCityPart();
        } elseif ($code == 'NP') {
            $address[0] = 'Na poštu';
            $address[1] = '';
            $address[2] = '';
            $address[3] = $pickupPoint[2]; // zip pickup
            $address[4] = $pickupPoint[1]; //obec pickup
            $address[5] = $pickupPoint[3]; //cast obce pickup
        } else {
            $address[0] = 'Balíkovna';
            $address[1] = '';
            $address[2] = '';
            $address[3] = $pickupPoint[2]; // zip pickup
            $address[4] = $pickupPoint[1]; //obec pickup
            $address[5] = $pickupPoint[3]; //cast obce pickup
        }

        $addressString = implode(';', $address);
        return $addressString;
    }

    //TODO: otestovat

    /**
     * Gets a service code from service name.
     * @param string $service
     * @return string code
     */
    public function getServiceCode(string $service) {
        if ($service == 'Balík Na poštu') {
            $code = 'NP';
        } elseif ($service == 'Balík Do ruky') {
            $code = 'DR';
        } elseif ($service == 'Balík Do balíkovny') {
            $code = 'NB';
        } else {
            throw new InvalidArgumentException();
        }
        return $code;
    }

    /**
     * Generates parcel ID number in format 'service code + new ID + ISO code'
     * e.g. id = ZZ123456789CZ; ZZ -parcel type, CZ - ISO code
     * @param string $service
     * @param Packet $packet
     * @return string parcelIdNumber
     */
    public function generateParcelIdNumber(string $service, Packet $packet)
    {

        $service_code = $this->getServiceCode($service);
        $newId = Strings::padLeft($packet->getID(), '9', 0);

        $end_code = 'CZ';

        return $service_code . $newId . $end_code;

    }

    //TODO. otestovat
    /**
     * Gets string representation from parcel COD. If there is no COD, it returns empty string.
     * @param $packet
     * @return string
     */
    public function getCod(Packet $packet) {
        if ($packet->cod->getValue() > 0) {
            return $packet->cod->getValue();
        } else {
            return '';
        }
    }

    /**
     * Splits pickup point name.
     * @param $pickupPoint
     * @return array
     */
    public function splitPickupPointName($pickupPoint) {
        $res = Strings::split($pickupPoint, '~, \s*~');
        return $res;
    }

    /**
     * Exports packet data into a new file.
     * @param string $location
     * @param string $sender
     * @param string $data
     * @return bool
     */
    private function exportData(string $location, string $sender, string $data)
    {
        $array = [];


        if (!file_exists($location)) {
            $saveResult = file_put_contents($location, $sender);
            if (!$saveResult)
            {
                Debugger::log('Nepodařilo se uložit data zásilky do souboru .csv', ILogger::ERROR);
                return FALSE;
            }
        }
        $saveResult = file_put_contents($location, $data, FILE_APPEND);
        if (!$saveResult)
        {
            Debugger::log('Nepodařilo se uložit data zásilky do souboru .csv', ILogger::ERROR);
            return FALSE;
        }


        return TRUE;

    }
}