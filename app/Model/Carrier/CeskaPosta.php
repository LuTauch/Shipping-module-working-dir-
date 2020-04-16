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
     * @var string $location of the local file
     */
    private $location;

    /**
     * path to local file
     */
    const POST_OFFICE_TEMP_FILE_CSV = __DIR__ . '/../temp/czechPostShipment/ceska-posta-';
    const WEEKEND_DELIVERY = '18+19';
    const EVENING_DELIVERY = '1B';
    const MULTI_PIECE_SHIPMENT = '70';

    public function __construct(Sender $sender)
    {
        $this->sender = $sender;
        //TODO: predelat na datum (ne datetime) -> pro každý den -> jiný soubor -> ten den ukládání všech zásilek do 1 souboru
        $this->location = self::POST_OFFICE_TEMP_FILE_CSV . new DateTime('today') . '-data.csv';
    }

    public function processOrder($service, Packet $packet)
    {
        $code = $this->getServiceCode($service);
        $senderData = $this->sender->getSenderData();
        $packetData = $this->getPacketData($service, $packet, $code);
        $res = $this->exportData($senderData, $packetData);

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
    //TODO: vyresit doplnkove sluzby a udanou cenu, popr. vicekusove zasilky
    public function getPacketData(string $service, Packet $packet, string $code) : string {
        $newId = $this->generateParcelIdNumber($service, $packet);
        $data = [];
        $data[0] = $newId;
        $data[1] = $packet->recipient['surname'] . $packet->recipient['name'];
        $data[2] = $this->getAddress($packet, $code);
        $data[8] = $packet->recipient['countryCode'];
        $data[9] = $packet->recipient['phoneNo'];
        $data[10] = $packet->recipient['email'];
        $data[11] = $this->getWeightInKg($packet);
        $data[12] = 'doplnkove-sluzby';
        $data[13] = '';
        $data[14] = $this->getCod($packet);
        $data[15] = '';
        $data[16] = $packet->getID();
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
        $pickupPoint = $this->splitPickupPointName($packet->recipient['pickupPoint']);
        if ($code == 'DR') {
            $address[0] = $packet->recipient['street'];
            $address[1] = '';
            $address[2] = $packet->recipient['houseNo'];
            $address[3] = $packet->recipient['zipCode'];
            $address[4] = $packet->recipient['city'];
            $address[5] = $packet->recipient['cityPart'];
        } elseif ($code == 'NP') {
            $address[0] = 'Na poštu';
            $address[1] = '';
            $address[2] = '';
            $address[3] = $pickupPoint[1]; // zip pickup
            $address[4] = $pickupPoint[2]; //obec pickup
            $address[5] = $pickupPoint[0]; //cast obce pickup
        } else {
            $address[0] = 'Balíkovna';
            $address[1] = '';
            $address[2] = '';
            $address[3] = $pickupPoint[1]; // zip pickup
            $address[4] = $pickupPoint[2]; //obec pickup
            $address[5] = $pickupPoint[0]; //cast obce pickup
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
        $newId = Strings::padLeft($packet['id'], '9', 0);
        $end_code = 'CZ';

        $parcelIdNumber = $service_code . $newId . $end_code;

        return $parcelIdNumber;
    }

    //TODO. otestovat
    /**
     * Gets parcel weight in kg (originally in grams)
     * @param $packet
     * @return float|int
     */
    public function getWeightInKg(Packet $packet) {
        return $packet->size['weight'] / 1000;
    }

    //TODO. otestovat
    /**
     * Gets string representation from parcel COD. If there is no COD, it returns empty string.
     * @param $packet
     * @return string
     */
    public function getCod(Packet $packet) {
        if ($packet->cod['value'] > 0) {
            return $packet->cod['value'];
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
        return Strings::split($pickupPoint, '~ , \s*~');
    }

    /**
     * Exports packet data into a new file.
     * @param string $sender
     * @param string $data
     * @return bool
     */
    private function exportData(string $sender, string $data)
    {
        $array = [];
        $array[0] = $sender;
        $array[1] = $data;

        $saveResult = file_put_contents($this->location, $array);
        if (!$saveResult)
        {
            Debugger::log('Nepodařilo se uložit data zásilky do souboru .csv', ILogger::ERROR);
            return FALSE;
        }

        return TRUE;

    }
}