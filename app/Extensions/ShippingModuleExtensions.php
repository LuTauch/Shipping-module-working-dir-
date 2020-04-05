<?php


namespace LuTauch\App\Extensions;


use LuTauch\App\Forms\IConfigFormFirstFactory;
use LuTauch\App\Forms\IConfigFormSecondFactory;
use LuTauch\App\Forms\IShipmentFormFirstFactory;
use LuTauch\App\Forms\IShipmentFormSecondFactory;
use LuTauch\App\Model\CarrierModel;
use LuTauch\App\Model\Factory\CarrierFactory;
use LuTauch\App\Model\Options;
use LuTauch\App\Model\OptionsModel;
use LuTauch\App\Model\OrderAcceptance;
use LuTauch\App\Model\PacketItems\Cod;
use LuTauch\App\Model\PacketItems\Packet;
use LuTauch\App\Model\PacketItems\Receiver;
use LuTauch\App\Model\PacketItems\Size;
use Nette\DI\CompilerExtension;
use Nette\DI\ServiceCreationException;

class ShippingModuleExtensions extends CompilerExtension
{

    const ESHOP = 'eshop';
    const CURRENCY ='currency';
    const COUNTRY_CODE = 'countryCode';

    const REQUIRED_PARAMS = [
        self::ESHOP,
        self::CURRENCY,
        self::COUNTRY_CODE
    ];

    private $eshop;
    private $currency;
    private $countryCode;


    public function loadConfiguration() {
        $builder = $this->getContainerBuilder();
        $this->setUpParams();

        $builder->addDefinition($this->prefix('LuTauch.OptionsModel'))
            ->setFactory(OptionsModel::class);

        $builder->addDefinition($this->prefix('LuTauch.CarrierModel'))
            ->setFactory(CarrierModel::class);

        $builder->addDefinition($this->prefix('LuTauch.ShippingFormFirst'))
            ->setFactory(IShipmentFormFirstFactory::class);

        $builder->addDefinition($this->prefix('LuTauch.ShippingFormSecond'))
            ->setFactory(IShipmentFormSecondFactory::class);

        $builder->addDefinition($this->prefix('LuTauch.ConfigFormFirst'))
            ->setFactory(IConfigFormFirstFactory::class);

        $builder->addDefinition($this->prefix('LuTauch.ConfigFormSecond'))
            ->setFactory(IConfigFormSecondFactory::class);

        $builder->addDefinition($this->prefix('LuTauch.Options'))
            ->setFactory(Options::class);

        $builder->addDefinition($this->prefix('LuTauch.CarrierFactory'))
            ->setFactory(CarrierFactory::class);

        $builder->addDefinition($this->prefix('LuTauch.Packet'))
            ->setFactory(Packet::class);

        $builder->addDefinition($this->prefix('LuTauch.Cod'))
            ->setFactory(Cod::class);

        $builder->addDefinition($this->prefix('LuTauch.Size'))
            ->setFactory(Size::class);

        $builder->addDefinition($this->prefix('LuTauch.Receiver'))
            ->setFactory(Receiver::class);

        $builder->addDefinition($this->prefix('LuTauch.OrderAcceptance'))
            ->setFactory(OrderAcceptance::class)
        ->setArguments([$this->eshop, $this->countryCode, $this->currency]);
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
    }


}