<?php

namespace App\Forms;

use Nette\Application\UI;
use App\Model\CarrierModel;
use Tracy\Debugger;

class ConfigFormSecond extends BaseComponent
{
    private $serviceIds;

    private $carrierModel;

    //predani zavislosti
    public function __construct(CarrierModel $carrierModel)
    {
        $this->carrierModel = $carrierModel;
    }

    public function setServiceIds($serviceIds)
    {
        $this->serviceIds = $serviceIds;
    }

    protected function createComponentForm(): UI\Form
    {
        $form = new UI\Form();

        $services = $this->carrierModel->getServicesForCheckboxList($this->serviceIds);

        $form->addCheckboxList('services', 'Služby', $services);
        $form->addSubmit('save', 'Uložit');
        $form->onSuccess[] = [$this, 'configFormServiceSucceeded'];
        return $form;
    }

    public function configFormServiceSucceeded(UI\Form $form, \stdClass $values)
    {
       $this->carrierModel->saveSelectedServiceIds($values->services);

       $this->presenter->flashMessage('Úspěšně se uložili vybraní dopravci.');
       $this->presenter->redirect('Homepage:');
    }
}