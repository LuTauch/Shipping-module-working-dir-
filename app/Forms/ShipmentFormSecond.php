<?php

namespace LuTauch\App\Forms;

use LuTauch\App\Model\Options;
use Nette\Application\UI;
use LuTauch\App\Model\CarrierModel;
use Nette\ComponentModel\IComponent;
use Nette\Forms\Form;
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
    public function createComponentForm(): UI\Form
    {
        $form = new UI\Form();
        //getting service names from service ids

        $services = $this->carrierModel->getServicesByIds($this->serviceIds)->fetchAll();
        $servicesWithPickup = $this->carrierModel->getServicesWithPickup()->select('service_id')->fetchAll();

        //sluzby
        $options = [];
        foreach ($services as $item)
        {
            $options[$item->service_id] = \Nette\Utils\Html::el('span')->setText($item->complete_name . ' (' . $item->price . ' Kč)')->addAttributes(['data-text' => $item->service_id]);
        }

        $form->addRadioList('services', 'Služby', $options)->setRequired();

        $form->addText('pickup_place', 'Odběrné místo')
            ->addConditionOn($form['services'], Form::IS_NOT_IN, $servicesWithPickup)
                ->addRule(Form::BLANK, 'Smažte prosím odběrné místo. Tento typ dopravce jej nepodporuje.')
            ->endCondition()
            ->addConditionOn($form['services'], Form::IS_IN, $servicesWithPickup)
            ->setRequired('Vyplňte prosím odběrné místo pro dopravce.')
        ->endCondition();




        $form->addSubmit('submit', 'Další');
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


    }
}