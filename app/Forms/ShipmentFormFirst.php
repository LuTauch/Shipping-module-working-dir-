<?php

namespace LuTauch\App\Forms;

use LuTauch\App\Model\CarrierModel;
use LuTauch\App\Model\CzechPostPickupPointModel;
use LuTauch\App\Model\Options;
use LuTauch\App\Model\ZasilkovnaPickupPointModel;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI;
use Nette\Forms\Form;

/**
 * Class ConfigFormSecond represents second step in the configuration process (final service selection).
 * It defines the configuration form and a way to process the data sent from it.
 * @package App\Forms
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
        $form->onSuccess[] = [$this, 'shipmentFormFirstSucceeded'];
        return $form;
    }

    /**
     * Is being called after successful submission of the second configuration form. It processes the data received from
     * the form, shows a message for the user and redirects him.
     * @param UI\Form $form
     * @param \stdClass $values
     * @throws \Nette\Application\AbortException
     */
    public function shipmentFormFirstSucceeded(UI\Form $form, \stdClass $values)
    {



    }

}

