<?php

namespace LuTauch\App\Forms;

use LuTauch\App\Enum\DeliveryEnum;
use LuTauch\App\Model\CarrierModel;
use LuTauch\App\Model\CzechPostPickupPointModel;
use LuTauch\App\Model\Options;
use LuTauch\App\Model\OptionsModel;
use LuTauch\App\Model\PickupPoint\CzechPostDoBalikovnyRepository;
use LuTauch\App\Model\PickupPoint\CzechPostNaPostuRepository;
use LuTauch\app\Model\Repository\ZasilkovnaPickupPointRepository;
use LuTauch\App\Model\ZasilkovnaPickupPointModel;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI;
use Nette\Forms\Form;
use Tracy\Debugger;
use function Sodium\add;


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

    public function handleAutocomplete() {

        $userInput = $this->presenter->getParameter('userInput');

        if ($this->serviceId == self::ID_CESKA_POSTA_BALIK_NA_POSTU ) {
            if (is_numeric(str_replace(" ", "", $userInput))){
                $res = $this->czechPostNaPostuRepository->filterAddressByZip($userInput);
            }
            else
            {
                $res = $this->czechPostNaPostuRepository->filterAddressByCity($userInput);
            }

        } elseif ($this->serviceId == self::ID_CESKA_POSTA_BALIK_DO_BALIKOVNY) {
            if (is_numeric(str_replace(" ", "", $userInput))){
                $res = $this->czechPostDoBalikovnyRepository->filterAddressByZip($userInput);
            }
            else
            {
                $res = $this->czechPostDoBalikovnyRepository->filterAddressByCity($userInput);
            }
        }
        else if ($this->serviceId == self::ID_ZASILKOVNA_NA_VYDEJNI_MISTA) {
            if (is_numeric(str_replace(" ", "", $userInput))){
                $res = $this->zasilkovnaPickupPointRepository->filterAddressByZip($userInput);
            }
            else
            {
                $res = $this->zasilkovnaPickupPointRepository->filterAddressByCity($userInput);
            }
        }
        Debugger::barDump($res);
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
        $form->addText('pickup_place', 'Výdejné místo (Zadejte město nebo PSČ oblasti.)')->setRequired();


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

