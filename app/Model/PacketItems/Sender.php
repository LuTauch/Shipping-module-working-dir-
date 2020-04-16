<?php


namespace LuTauch\App\Model\PacketItems;


use Tracy\Debugger;

class Sender
{
    private $senderName;
    private $senderZip;
    private $senderCity;
    private $senderCityPart;
    private $senderStreet;
    private $senderStreetNr;
    private $senderPhone;
    private $senderEmail;

    public function __construct($senderName, $senderZip, $senderCity, $senderCityPart, $senderStreet, $senderStreetNr, $senderPhone, $senderEmail)
    {
        $this->senderName = $senderName;
        $this->senderZip = $senderZip;
        $this->senderCity = $senderCity;
        $this->senderCityPart = $senderCityPart;
        $this->senderStreet = $senderStreet;
        $this->senderStreetNr = $senderStreetNr;
        $this->senderPhone = $senderPhone;
        $this->senderEmail = $senderEmail;
    }

    /**
     * Gets sender data in csv format.
     * Format is: 'code;sender name;zip code;city;city part;street;streetNr;phone;email;\n'
     * @return string
     */
    public function getSenderData() : string {
        $data = [];
        $data[0] = '';
        $data[1] = $this->senderName;
        $data[2] = $this->senderZip;
        $data[3] = $this->senderCity;
        $data[4] = $this->senderCityPart;
        $data[5] = $this->senderStreet;
        $data[6] = $this->senderStreetNr;
        $data[7] =  $this->senderPhone;
        $data[8] =  $this->senderEmail;
        $data[9] = '\n';

        return implode(';', $data);
    }

}