<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\ComponentModel\IComponent;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    public function createComponentConfigFormFirst(): ?IComponent
    {
        return $this->context->createService("ConfigFormFirst");
    }
}
