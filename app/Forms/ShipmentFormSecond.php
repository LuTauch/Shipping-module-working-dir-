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
 * Class ConfigFormFirst represents first step in the configuration process (e-shop categorisation and service preference).
 * It defines a configuration form and a way to process the data sent from it.
 * @package App\Forms
 */
class ShipmentFormSecond extends BaseComponent
{





    /** @var CarrierModel */
    private $carrierModel;

    /**
     * @var Options
     */
    private $options;

    /**
     * @var CzechPostPickupPointModel
     */
    private $czechPostPickupPointModel;

    /**
     * @var ZasilkovnaPickupPointModel
     */
    private $zasilkovnaPickupPointModel;

    private $pickupPlaceInput;

    /**
     * ConfigFormSecond constructor (dependency handover)
     * @param CarrierModel $carrierModel
     * @param CzechPostPickupPointModel $czechPostPickupPointModel
     * @param ZasilkovnaPickupPointModel $zasilkovnaPickupPointModel
     */
    public function __construct(CarrierModel $carrierModel, CzechPostPickupPointModel $czechPostPickupPointModel, ZasilkovnaPickupPointModel $zasilkovnaPickupPointModel)
    {
        $this->carrierModel = $carrierModel;
        $this->czechPostPickupPointModel = $czechPostPickupPointModel;
        $this->zasilkovnaPickupPointModel = $zasilkovnaPickupPointModel;
    }


    public function setOption(Options $options) {
        $this->options = $options;
    }

    public function handleAutocomplete($carrier) {

        if ($carrier == 'ceska_posta') {
            $res = $this->czechPostPickupPointModel->findAll()->fetchAll();
        } else if ($carrier == 'zasilkovna') {
            $res = $this->zasilkovnaPickupPointModel->findAll()->fetchAll();
        }
        $this->presenter->sendResponse(new JsonResponse($res));

        $this->presenter->terminate();

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
        $form->onSuccess[] = [$this, 'shipmentFormSecondSucceeded'];
        return $form;
    }

    public function extendForm(Form $form)
    {
        $form->addCheckboxList('additionalService', 'Doplňkové služby', [
            'weekend_delivery' => 'Víkendové doručení',
            'evening_delivery' => 'Večerní doručení',
            'express_delivery' => 'Expresní doručení',
        ]);

        $servicesWithPickup = $this->carrierModel->getServicesWithPickup()->select('service_id')->fetchAll();
        $form->addText('pickup_place', 'Odběrné místo')
            ->addConditionOn($form['services'], Form::IS_NOT_IN, $servicesWithPickup)
            ->addRule(Form::BLANK, 'Smažte prosím odběrné místo. Tento typ dopravce jej nepodporuje.')
            ->endCondition()
            ->addConditionOn($form['services'], Form::IS_IN, $servicesWithPickup)
            ->setRequired('Vyplňte prosím odběrné místo pro dopravce.')
            ->endCondition();


        return $form;
    }

    /**
     * Is being called after successful submission of the first configuration form. It processes the data from the form, gets service ids available
     * for the user from the database and sends them to the second configuration form (by redirect).
     * @param UI\Form $form
     * @param \stdClass $values
     * @throws \Nette\Application\AbortException
     */
    public function shipmentFormSecondSucceeded(UI\Form $form, \stdClass $values)
    {

    }
}

