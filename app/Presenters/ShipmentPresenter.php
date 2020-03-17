<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\ComponentModel\IComponent;
use Tracy\Debugger;


final class ShipmentPresenter extends Nette\Application\UI\Presenter
{

    private $serviceIds;

    public function createComponentShipmentFormFirst(): ?IComponent
    {
        return $this->context->createService("ShipmentFormFirst");
    }

    public function createComponentShipmentFormSecond(): ?IComponent
    {
        $service = $this->context->createService("ShipmentFormSecond");
        $service->setServiceIds($this->serviceIds);

        return $service;
    }


    public function actionStep2(array $serviceIds)
    {
        $this->serviceIds = $serviceIds;
    }
}
