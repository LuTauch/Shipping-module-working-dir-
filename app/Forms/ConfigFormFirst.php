<?php

namespace App\Forms;

use App\Model\CarrierModel;
use Nette\Application\UI;
use Nette\Forms\Form;


class ConfigFormFirst extends BaseComponent
{
    private $carrierModel;

    //predani zavislosti
    public function __construct(CarrierModel $carrierModel)
    {
        $this->carrierModel = $carrierModel;
    }


    protected function createComponentForm(): UI\Form
    {
        $form = new UI\Form();
        $form->setMethod('POST');

        $form->addCheckboxList('packet', 'Typ zásilek', [
            'packet_s' => 'S (hmotnost do 20 kg a nejdelší strana do 175 cm)',
            'packet_m' => 'M (hmotnost do 30 (31,5) kg a nejdelší strana do 240 (120) cm)',
            'packet_l' => 'L (hmotnost do 50 kg a nejdelší strana do 240 cm)',
            'packet_xl' => 'Nadlimitní zásilky (rozměry neomezeny)',
        ]);

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
        //TODO: ruční preference dopravce


        $form->addSubmit('submit', 'Odeslat');
        $form->onSuccess[] = [$this, 'configFormSucceeded'];
        return $form;
    }

    public function configFormSucceeded(UI\Form $form, \stdClass $values)
    {
       $availableServiceIds =  $this->carrierModel->findServiceIds($values);
        $this->presenter->redirect('Homepage:step2', [$availableServiceIds]); //pres presenter




        //TODO zpracovat formulář
        //poslat data do dalšího formuláře a tam se napojit na db nebo se začít bavit s db tady
        // dotaz na vrácení všech služeb z tabulky service, které odpovídají těmto službám.
        //potom až přesměrovat na další formulář

        //kontrola typu zásilky -> projít hodnoty checkboxlistu 'packetSize' -> pole packetSizes obsahuje jen zaškrtnuté položky
        //$form->onSuccess[] = function ($form, array $values) {
        //$packetSizes = $values['packetSize'];


        //kontrola typu doručení -> zjistit, která hodnota v radiolistu 'deliveryOption'byla zaskrtnuta
        //if (#values['deliveryOption'] == 'oboji') $deliveryTypes = [onAddress, pickup]
        // else ($deliveryTypes = #values['deliveryOption']

        //kontrola typů doplňkových služeb -> projít hodnoty checkboxlistu 'additionalService' -> pole additionalServices obsahuje jen zaškrtnuté položky
        //$form->onSuccess[] = function ($form, array $values) {
        //$additionalServices = $values['additionalService'];
        //};

        //$values['additionalService'];

        //$services = CarrierModel.php --> findService($packetSizes, $deliveryTypes, $additionalServices);
        //$success = update($services);



    }

    public function update($selected) { //pole?
        //nova funkce pro update vybranych dopravcu v tabulce - foreach přes všechny $selected
        //foreach($selected as $item) {
        // CarrierModel.php -> saveSelected($item);
        //}
    }


}