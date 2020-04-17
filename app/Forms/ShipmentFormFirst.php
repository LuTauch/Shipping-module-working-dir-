<?php

namespace LuTauch\App\Forms;

use LuTauch\App\Model\CarrierModel;
use LuTauch\App\Model\Options;
use Nette\Application\UI;
use Tracy\Debugger;

/**
 * Class ShipmentFormFirst
 * @package LuTauch\App\Forms
 */
class ShipmentFormFirst extends BaseComponent
{
    /**
     * @var array $serviceIds ids of services to be selected
     */
    private $serviceIds;

    /**
     * @var CarrierModel
     */
    private $carrierModel;


    /**
     * ConfigFormFirst constructor. important for dependency handover.
     * @param CarrierModel $carrierModel
     * @param Options $options
     */
    public function __construct(CarrierModel $carrierModel)
    {
        $this->carrierModel = $carrierModel;
    }

    /**
     * @param array $serviceIds
     */
    public function setServiceIds(array $serviceIds): void
    {
        $this->serviceIds = $serviceIds;
    }


    /** Creates a configuration form with a checkboxlist containing service names
     * @throws UI\InvalidLinkException
     */
    public function createComponentForm(): UI\Form
    {
        $form = new UI\Form();
        //getting service names from service ids
        $services = $this->carrierModel->getServicesByIds($this->serviceIds)->fetchAll();

        //sluzby
        $options = [];
        foreach ($services as $item)
        {
            $options[$item->service_id] = \Nette\Utils\Html::el('span')->setText($item->complete_name . ' (' . $item->price . ' Kč)')->addAttributes(['data-text' => $item->service_id]);
        }

        $form->addRadioList('services', 'Služby', $options)->setRequired();

        $form->addSubmit('submit', 'Další');
        //onsubmit
        $form->onSuccess[] = [$this, 'shipmentFormFirstSucceeded'];
        return $form;
    }

    /**
     * @param UI\Form $form
     * @param \stdClass $values
     * @throws \Nette\Application\AbortException
     */
    public function shipmentFormFirstSucceeded(UI\Form $form, \stdClass $values)
    {
       //vrati se mi id sluzby
        $additionalServices = $this->carrierModel->findAdditionalServices($values->services);
        $this->presenter->redirect('Shipment:step2', [$additionalServices]);




    }

}

