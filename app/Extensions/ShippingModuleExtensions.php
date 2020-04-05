<?php


namespace LuTauch\App\Extensions;

use LuTauch\App\Model\OrderAcceptance;
use LuTauch\App\Model\PickupPoint\ZasilkovnaPickupPoint;
use LuTauch\app\Model\Repository\ZasilkovnaPickUpPointDownload;
use Nette\DI\CompilerExtension;
use Nette\DI\ServiceCreationException;

class ShippingModuleExtensions extends CompilerExtension
{

    const ESHOP = 'eshop';
    const CURRENCY ='currency';
    const COUNTRY_CODE = 'countryCode';
    const ZASILKOVNA_API_KEY = 'zasilkovnaApiKey';
    const ZASILKOVNA_URL = 'zasilkovnaUrl';

    const REQUIRED_PARAMS = [
        self::ESHOP,
        self::CURRENCY,
        self::COUNTRY_CODE,
        self::ZASILKOVNA_URL,
        self::ZASILKOVNA_API_KEY
    ];

    private $eshop;
    private $currency;
    private $countryCode;
    private $zasilkovnaUrl;
    private $zasilkovnaApiKey;


    public function loadConfiguration() {
        $builder = $this->getContainerBuilder();
        $this->setUpParams();

//        $builder->addDefinition($this->prefix('LuTauch.OptionsModel'))
//            ->setFactory(OptionsModel::class);
//
//        $builder->addDefinition($this->prefix('LuTauch.CarrierModel'))
//            ->setFactory(CarrierModel::class);
//
//        $builder->addDefinition($this->prefix('LuTauch.ShipmentFormFirst'))
//            ->setClass(IShipmentFormFirstFactory::class);
//
//        $builder->addDefinition($this->prefix('LuTauch.ShipmentFormSecond'))
//            ->setClass(IShipmentFormSecondFactory::class);
//
//        $builder->addDefinition($this->prefix('LuTauch.ConfigFormFirst'))
//            ->setClass(IConfigFormFirstFactory::class);
//
//        $builder->addDefinition($this->prefix('LuTauch.ConfigFormSecond'))
//            ->setClass(IConfigFormSecondFactory::class);
//
//        $builder->addDefinition($this->prefix('LuTauch.Options'))
//            ->setFactory(Options::class);
//
//        $builder->addDefinition($this->prefix('LuTauch.CarrierFactory'))
//            ->setFactory(CarrierFactory::class);
//
//        $builder->addDefinition($this->prefix('LuTauch.Packet'))
//            ->setFactory(Packet::class);
//
//        $builder->addDefinition($this->prefix('LuTauch.Cod'))
//            ->setFactory(Cod::class);
//
//        $builder->addDefinition($this->prefix('LuTauch.Size'))
//            ->setFactory(Size::class);
//
//        $builder->addDefinition($this->prefix('LuTauch.Receiver'))
//            ->setFactory(Receiver::class);

        $this->compiler->loadConfig(__DIR__ . '/../config/common.neon');

        $builder->addDefinition($this->prefix('LuTauch.OrderAcceptance'))
            ->setFactory(OrderAcceptance::class)
        ->setArguments([$this->eshop, $this->countryCode, $this->currency]);

        $builder->addDefinition($this->prefix('LuTauch.ZasilkovnaPickupPointDownload'))
            ->setFactory(ZasilkovnaPickUpPointDownload::class)
            ->setArguments([$this->zasilkovnaApiKey, $this->zasilkovnaUrl]);
    }

    public function setUpParams(){
        $params = $this->getConfig();

        foreach (self::REQUIRED_PARAMS as $param) {
            if (!isset($params[$param])) {
                throw new ServiceCreationException('neco ti tam chybí brouku!');
            }
        }

        $this->eshop = $params[self::ESHOP];
        $this->currency = $params[self::CURRENCY];
        $this->countryCode = $params[self::COUNTRY_CODE];
        $this->zasilkovnaApiKey = $params[self::ZASILKOVNA_API_KEY];
        $this->zasilkovnaUrl = $params[self::ZASILKOVNA_URL];
    }


}