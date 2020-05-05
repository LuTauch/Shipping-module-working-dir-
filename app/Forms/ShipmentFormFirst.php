<?php

namespace LuTauch\App\Forms;

use App\Model\OrderSession;
use LuTauch\App\Model\Repository\CarrierModel;
use Nette\Application\AbortException;
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
     * @var OrderSession
     */
    private $orderSession;


    /**
     * ConfigFormFirst constructor. important for dependency handover.
     * @param CarrierModel $carrierModel
     */
    public function __construct(CarrierModel $carrierModel, OrderSession $orderSession)
    {
        $this->carrierModel = $carrierModel;
        $this->orderSession = $orderSession;
    }

    /**
     * @param array $serviceIds
     */
    public function setAvailableServiceIds(array $serviceIds): void
    {
        $this->serviceIds = $serviceIds;
    }


    /** Creates a configuration form with a checkboxlist containing service names
     */
    public function createComponentForm(): UI\Form
    {
        $form = new UI\Form();
        //getting service names from service ids
        $services = $this->carrierModel->getServicesByIds($this->serviceIds)->fetchAll();

        //sluzby
        $options = [];
        foreach ($services as $item) {
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
     * @throws AbortException
     */
    public function shipmentFormFirstSucceeded(UI\Form $form, \stdClass $values)
    {
        $this->orderSession->addValue('serviceId', $values->services);
        $pickup = $this->carrierModel->getServicesWithPickup($values->services)[0];
        $availableService = $this->carrierModel->findAdditionalServices($values->services);
        $empty = 0;
        foreach ($availableService as $service) {
            if (!empty($service)) {
                $empty = 1;
            }
        }
        //pokud nemam ani doplnkove sluzby ani pickup -> rovnou overview
        if ((!$pickup) && (!$empty)) {
            $this->presenter->redirect('Order:Overview');
        }

        $this->presenter->redirect('Shipment:step2', [$this->serviceIds], [$values->services]);
    }

}

