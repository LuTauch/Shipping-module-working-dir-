<?php


namespace LuTauch\App\Extensions;

use LuTauch\App\OrderAcceptance;
use LuTauch\App\Model\PacketItems\Sender;
use LuTauch\app\Model\PickupPoint\ZasilkovnaPickUpPointDownload;
use Nette\DI\CompilerExtension;
use Nette\DI\ServiceCreationException;

class ShippingModuleExtensions extends CompilerExtension
{

    const ESHOP = 'eshop';
    const CURRENCY = 'currency';
    const COUNTRY_CODE = 'countryCode';
    const ZASILKOVNA_API_KEY = 'zasilkovnaApiKey';
    const ZASILKOVNA_URL = 'zasilkovnaUrl';

    const SENDER_NAME = 'senderName';
    const SENDER_ZIP = 'senderZip';
    const SENDER_CITY = 'senderCity';
    const SENDER_CITY_PART = 'senderCityPart';
    const SENDER_STREET = 'senderStreet';
    const SENDER_STREET_NR = 'senderStreetNr';
    const SENDER_PHONE = 'senderPhone';
    const SENDER_EMAIL = 'senderEmail';


    const REQUIRED_PARAMS = [
        self::ESHOP,
        self::CURRENCY,
        self::COUNTRY_CODE,
        self::ZASILKOVNA_URL,
        self::ZASILKOVNA_API_KEY,
        self::SENDER_NAME,
        self::SENDER_ZIP,
        self::SENDER_CITY,
        self::SENDER_CITY_PART,
        self::SENDER_STREET,
        self::SENDER_STREET_NR,
        self::SENDER_PHONE,
        self::SENDER_EMAIL
    ];

    private $eshop;
    private $currency;
    private $countryCode;
    private $zasilkovnaUrl;
    private $zasilkovnaApiKey;
    private $senderName;
    private $senderZip;
    private $senderCity;
    private $senderCityPart;
    private $senderStreet;
    private $senderStreetNr;
    private $senderPhone;
    private $senderEmail;

    public function loadConfiguration()
    {

        $builder = $this->getContainerBuilder();
        $this->setUpParams();
        $this->compiler->loadConfig(__DIR__ . '/../config/common.neon');

        $builder->addDefinition($this->prefix('LuTauch.OrderAcceptance'))
            ->setFactory(OrderAcceptance::class)
            ->setArguments([$this->eshop, $this->countryCode, $this->currency]);

        $builder->addDefinition($this->prefix('LuTauch.ZasilkovnaPickupPointDownload'))
            ->setFactory(ZasilkovnaPickUpPointDownload::class)
            ->setArguments([$this->zasilkovnaApiKey, $this->zasilkovnaUrl]);

        $builder->addDefinition($this->prefix('LuTauch.Sender'))
            ->setFactory(Sender::class)
            ->setArguments([$this->senderName, $this->senderZip, $this->senderCity, $this->senderCityPart, $this->senderStreet, $this->senderStreetNr, $this->senderPhone, $this->senderEmail]);
    }

    /**
     *
     */
    public function setUpParams(): void
    {
        $params = $this->getConfig();

        foreach (self::REQUIRED_PARAMS as $param) {
            if (!isset($params[$param])) {
                throw new ServiceCreationException('Problém s načtením parametrů z modulu!');
            }
        }

        $this->eshop = $params[self::ESHOP];
        $this->currency = $params[self::CURRENCY];
        $this->countryCode = $params[self::COUNTRY_CODE];
        $this->zasilkovnaApiKey = $params[self::ZASILKOVNA_API_KEY];
        $this->zasilkovnaUrl = $params[self::ZASILKOVNA_URL];

        $this->senderName = $params[self::SENDER_NAME];
        $this->senderZip = $params[self::SENDER_ZIP];
        $this->senderCity = $params[self::SENDER_CITY];
        $this->senderCityPart = $params[self::SENDER_CITY_PART];
        $this->senderStreet = $params[self::SENDER_STREET];
        $this->senderStreetNr = $params[self::SENDER_STREET_NR];
        $this->senderPhone = $params[self::SENDER_PHONE];
        $this->senderEmail = $params[self::SENDER_EMAIL];

    }


}