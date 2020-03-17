<?php

namespace App\Forms;

use App\Model\CarrierModelPhase2;
use Nette\Application\UI;
use App\Model\CarrierModel;
use Tracy\Debugger;

/**
 * Class ConfigFormSecond represents second step in the configuration process (final service selection).
 * It defines the configuration form and a way to process the data sent from it.
 * @package App\Forms
 */
class ShipmentFormSecond extends BaseComponent
{
    /**
     * @var serviceids ids of services to be selected
     */
    private $serviceIds;

    /**
     * @var CarrierModel
     */
    private $carrierModelPhase2;

    /**
     * ConfigFormSecond constructor (dependency handover)
     * @param CarrierModel $carrierModel
     */
    public function __construct(CarrierModelPhase2 $carrierModelPhase2)
    {
        $this->carrierModelPhase2 = $carrierModelPhase2;
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
    public function createComponentForm(): UI\Form
    {
        $form = new UI\Form();
        //getting service names from service ids
        $services = $this->carrierModelPhase2->getServicesForCheckboxList($this->serviceIds);

        //sluzby
        $form->addCheckboxList('services', 'Služby', $services);


        $form->addSubmit('next', 'Další');
        $form->onSuccess[] = [$this, 'shipmentFormSecondSucceeded'];
        return $form;
    }

    /**
     * Is being called after successful submission of the second configuration form. It processes the data received from
     * the form, shows a message for the user and redirects him.
     * @param UI\Form $form
     * @param \stdClass $values
     * @throws \Nette\Application\AbortException
     */
    public function shipmentFormSecondSucceeded(UI\Form $form, \stdClass $values)
    {
        //uložit vybrané do instance Packet

        $this->presenter->flashMessage('');
        $this->presenter->redirect('');
    }
}