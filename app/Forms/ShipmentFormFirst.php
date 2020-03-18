<?php

namespace App\Forms;

use App\Model\CarrierModel;
use App\Model\CarrierModelPhase2;
use Nette\Application\UI;
use Nette\Forms\Form;
use Tracy\Debugger;


/**
 * Class ConfigFormFirst represents first step in the configuration process (e-shop categorisation and service preference).
 * It defines a configuration form and a way to process the data sent from it.
 * @package App\Forms
 */
class ShipmentFormFirst extends BaseComponent
{
    /** @var CarrierModel */
    private $carrierModelPhase2;

    /**
     * ConfigFormFirst constructor. important for dependency handover.
     * @param CarrierModel $carrierModel
     */
    public function __construct(CarrierModelPhase2 $carrierModelPhase2)
    {
        $this->carrierModelPhase2 = $carrierModelPhase2;
    }

    /**
     * Creates a configuration component consisting of 3 option groups (checkboxlists).
     * @return UI\Form
     */
    protected function createComponentForm(): UI\Form
    {

        $form = new UI\Form();
        $form->setMethod('POST');

        $form = $this->extendForm($form);

        $form->addSubmit('submit', 'Odeslat');
        //setting on success method
        $form->onSuccess[] = [$this, 'shipmentFormSucceeded'];
        return $form;
    }

    public function extendForm(Form $form)
    {
        //toto dat mozna do samostatneho kontejneru?
        $delivery = [
            'address_delivery' => 'Na adresu',
            'pickup_delivery' => 'Na výdejní místo',
        ];
        $form->addCheckboxList('deliveryOption', 'Způsob doručení:', $delivery);
        $form->addCheckboxList('additionalService', 'Doplňkové služby', [
            'cash_on_delivery' => 'Dobírka',
            'weekend_delivery' => 'Víkendové doručení',
            'evening_delivery' => 'Večerní doručení',
            //'express_delivery' => 'Expresní doručení',
        ]);

        return $form;
    }

    /**
     * Is being called after successful submission of the first configuration form. It processes the data from the form, gets service ids available
     * for the user from the database and sends them to the second configuration form (by redirect).
     * @param UI\Form $form
     * @param \stdClass $values
     * @throws \Nette\Application\AbortException
     */
    public function shipmentFormSucceeded(UI\Form $form, \stdClass $values)
    {
        $availableServiceIds = $this->carrierModelPhase2->findServiceIds($values);

        /*if($availableServiceIds === [])
        {
            $this->presenter->flashMessage('Bohužel pro vybrané služby není žádný dopravce. Zkuste zvolit jiné kombinace.', 'danger');
            $this->presenter->redirect('this');
        }*/

        $this->presenter->redirect('Shipment:step2', [$availableServiceIds]); //pres presenter


    }

}