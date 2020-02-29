<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\ComponentModel\IComponent;
use Tracy\Debugger;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{

    private $serviceIds;

    public function createComponentConfigFormFirst(): ?IComponent
    {
        return $this->context->createService("ConfigFormFirst");
    }
    public function createComponentConfigFormSecond(): ?IComponent
    {
        $service = $this->context->createService("ConfigFormSecond");
        $service->setServiceIds($this->serviceIds);

        return $service;
    }

    public function actionStep2(array $serviceIds)
    {
        $this->serviceIds = $serviceIds;
    }
}
