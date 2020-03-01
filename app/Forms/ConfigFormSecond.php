<?php

namespace App\Forms;

use Nette\Application\UI;
use App\Model\CarrierModel;
use Tracy\Debugger;

/**
 * Class ConfigFormSecond represents second step in the configuration process (final service selection).
 * It defines the configuration form and a way to process the data sent from it.
 * @package App\Forms
 */
class ConfigFormSecond extends BaseComponent
{
    /**
     * @var serviceids ids of services to be selected
     */
    private $serviceIds;

    /**
     * @var CarrierModel
     */
    private $carrierModel;

    /**
     * ConfigFormSecond constructor (dependency handover)
     * @param CarrierModel $carrierModel
     */
    public function __construct(CarrierModel $carrierModel)
    {
        $this->carrierModel = $carrierModel;
    }

    /**
     * Sets service ids
     * @param $serviceIds
     */
    public function setServiceIds($serviceIds)
    {
        $this->serviceIds = $serviceIds;
    }

    /** Creates a configuration form with a checkboxlist containing service names */
    protected function createComponentForm(): UI\Form
    {
        $form = new UI\Form();
        //getting service names from service ids
        $services = $this->carrierModel->getServicesForCheckboxList($this->serviceIds);

        $form->addCheckboxList('services', 'Služby', $services);
        $form->addSubmit('save', 'Uložit');
        $form->onSuccess[] = [$this, 'configFormServiceSucceeded'];
        return $form;
    }

    /**
     * Is being called after successful submission of the second configuration form. It processes the data received from
     * the form, shows a message for the user and redirects him.
     * @param UI\Form $form
     * @param \stdClass $values
     * @throws \Nette\Application\AbortException
     */
    public function configFormServiceSucceeded(UI\Form $form, \stdClass $values)
    {
        $this->carrierModel->saveSelectedServiceIds($values->services);

        $this->presenter->flashMessage('Vybrané služby byly uloženy.');
        $this->presenter->redirect('Configuration:');
    }
}