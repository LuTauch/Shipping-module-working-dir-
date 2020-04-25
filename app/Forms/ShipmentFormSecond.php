<?php

namespace LuTauch\App\Forms;

use LuTauch\App\Model\PickupPoint\CzechPostDoBalikovnyRepository;
use LuTauch\App\Model\PickupPoint\CzechPostNaPostuRepository;
use LuTauch\App\Model\Repository\CarrierModel;
use LuTauch\App\Model\Repository\Options;
use LuTauch\App\Model\Repository\OptionsModel;
use LuTauch\app\Model\Repository\ZasilkovnaPickupPointRepository;
use Nette\Application\AbortException;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI;
use Nette\Forms\Form;


/**
 * Class ShipmentFormSecond
 * @package LuTauch\App\Forms
 */
class ShipmentFormSecond extends BaseComponent
{

    /**
     * @var array $additionalServices ids of services to be selected
     */
    private $additionalServices;

    private $availableServiceIds;

    private $serviceId;

    /** @var CarrierModel */
    private $carrierModel;

    /**
     * @var Options
     */
    private $options;



    private $pickupPlaceInput;
    /**
     * @var OptionsModel
     */
    private $optionsModel;

    //TODO predavat pomoci extensions
    const ID_CESKA_POSTA_BALIK_DO_BALIKOVNY = 8;
    const ID_CESKA_POSTA_BALIK_NA_POSTU = 7;
    const ID_ZASILKOVNA_NA_VYDEJNI_MISTA = 9;
    /**
     * @var CzechPostNaPostuRepository
     */
    private $czechPostNaPostuRepository;
    /**
     * @var CzechPostDoBalikovnyRepository
     */
    private $czechPostDoBalikovnyRepository;
    /**
     * @var ZasilkovnaPickupPointRepository
     */
    private $zasilkovnaPickupPointRepository;


    /**
     * ConfigFormSecond constructor (dependency handover)
     * @param CarrierModel $carrierModel

     */
    public function __construct(CarrierModel $carrierModel, OptionsModel $optionsModel,
                                CzechPostNaPostuRepository $czechPostNaPostuRepository,
                                CzechPostDoBalikovnyRepository $czechPostDoBalikovnyRepository,
                                ZasilkovnaPickupPointRepository $zasilkovnaPickupPointRepository)
    {
        $this->carrierModel = $carrierModel;
        $this->optionsModel = $optionsModel;

        $this->czechPostNaPostuRepository = $czechPostNaPostuRepository;
        $this->czechPostDoBalikovnyRepository = $czechPostDoBalikovnyRepository;

        $this->zasilkovnaPickupPointRepository = $zasilkovnaPickupPointRepository;
    }

    public function render()
    {
        $this->template->availableServices = $this->availableServiceIds[0];
        parent::render();
    }

    public function setSelectedServiceId(string $serviceId): void
    {
        $this->serviceId = $serviceId;
    }

    /**
     * @param array $availableServiceIds
     */
    public function setAvailableServiceIds(array $availableServiceIds): void
    {
        $this->availableServiceIds = $availableServiceIds;
    }

    /**
     * @param array $additionalServices
     */
    public function setAdditionalServices($additionalServices): void

    {
        $this->additionalServices = $additionalServices;
    }



    public function setOption(Options $options) {
        $this->options = $options;
    }

    /**
     * @throws AbortException
     */
    public function handleAutocomplete() {
        $userInput = $this->getPresenter()->getParameter('userInput');

        if ($this->serviceId == self::ID_CESKA_POSTA_BALIK_NA_POSTU ) {
           $res = $this->czechPostNaPostuRepository->filterAddress($userInput);

        } elseif ($this->serviceId == self::ID_CESKA_POSTA_BALIK_DO_BALIKOVNY) {
            $res = $this->czechPostDoBalikovnyRepository->filterAddress($userInput);
        }
        else if ($this->serviceId == self::ID_ZASILKOVNA_NA_VYDEJNI_MISTA) {
            $res = $this->zasilkovnaPickupPointRepository->filterAddress($userInput);
        }

        $this->presenter->sendResponse(new JsonResponse($res));
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
        $additionalServiceArray = $this->optionsModel->getAdditionalServiceArray($this->additionalServices);
        $form->addCheckboxList('additionalService', 'Doplňkové služby', $additionalServiceArray);

        if (!empty($additionalServiceArray)) {
            $form->addText('warning', '');
        } else {
            $form->addText('warning', 'Pro vybranou službu nejsou dostupné žádné doplňkové služby');
        }

        if ($this->serviceId == self::ID_CESKA_POSTA_BALIK_NA_POSTU || $this->serviceId == self::ID_CESKA_POSTA_BALIK_DO_BALIKOVNY
            || $this->serviceId == self::ID_ZASILKOVNA_NA_VYDEJNI_MISTA) {
            $form->addText('pickup_place', 'Výdejní místo (Zadejte město, ulici či psč oblasti.)')
                ->setHtmlId('pickup-place');
        } else {
            $form->addText('pickup_place', 'Pro vybranou službu není služba doručení na výdejní místo dostupná.')
            ->setHtmlId('pickup-place-hidden')->setDisabled();
        }

        $form->addHidden('psc')
            ->setHtmlId('psc-hidden');

        return $form;
    }



    /**
     * Is being called after successful submission of the first configuration form. It processes the data from the form, gets service ids available
     * for the user from the database and sends them to the second configuration form (by redirect).
     * @param UI\Form $form
     * @param \stdClass $values
     * @throws AbortException
     */
    public function shipmentFormSecondSucceeded(UI\Form $form, \stdClass $values)
    {

    }
}

