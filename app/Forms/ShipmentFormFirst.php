<?php

namespace LuTauch\App\Forms;

use App\Model\CartSession;
use LuTauch\App\Model\CarrierModel;
use LuTauch\App\Model\Options;
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
    private $carrierModel;

    /**
     * @var Options
     */
    private $options;

    /**
     * ConfigFormFirst constructor. important for dependency handover.
     * @param CarrierModel $carrierModel
     * @param Options $options
     */
    public function __construct(CarrierModel $carrierModel)
    {
        $this->carrierModel = $carrierModel;
    }

    public function setOption(Options $options) {
        $this->options = $options;
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

        $form->addSubmit('submit', 'Další');
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
        //Debugger::barDump( $this->carrierModel->findServiceIdsFromSelected($this->options->getWeight(), $values));die;
        $availableServiceIds = $this->carrierModel->findServiceIdsFromSelected($this->options->getWeight(), $values);
        $this->presenter->redirect('Shipment:step2', [$availableServiceIds]);




    }

}
