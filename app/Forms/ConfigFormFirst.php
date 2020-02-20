<?php

namespace App\Forms;

use Nette\Application\UI;

class ConfigFormFirst extends UI\Control
{
    protected function showCarriers(UI\Form $form)
    {
        //TODO zobraz dopravce k rucnimu vyberu
    }

    public function render()
    {
        $this->template->setFile(__DIR__.'/ConfigFormFirst.latte');

        $this->template->render();
    }

    protected function createComponentForm(): UI\Form
    {
        $form = new UI\Form();
        $form->addCheckboxList('packetSize', 'Typ zásilek', [
            's' => 'S (hmotnost do 20 kg a nejdelší strana do 175 cm)',
            'm' => 'M (hmotnost do 30 (31,5) kg a nejdelší strana do 240 (120) cm)',
            'l' => 'L (hmotnost do 50 kg a nejdelší strana do 240 cm)',
            'xl' => 'Nadlimitní zásilky (rozměry neomezeny)',
        ]);

        //toto dat mozna do samostatneho kontejneru?
        $delivery = [
            'onAddress' => 'Na adresu',
            'onPickup' => 'Na výdejní místo',
            'both' => 'Obojí',
        ];
        $form->addRadioList('deliveryOption', 'Způsob doručení:', $delivery);

        $form->addCheckboxList('additionalService', 'Doplňkové služby', [
            'cod' => 'Dobírka',
            'weekend' => 'Víkendové doručení',
            'express' => 'Expresní doručení',
        ]);

        // $form->addButton('showCarriers', 'Ručně vybrat dopravce')->setHtmlAttribute('onclick', 'showCarriers($this)'); //TODO

        $form->addSubmit('submit', 'Odeslat');
        $form->onSuccess[] = [$this, 'configFormSucceeded'];
        return $form;
    }

    protected function configFormSucceeded(UI\Form $form, \stdClass $values)
    {
        //TODO zpracovat formulář
        //poslat data do dalšího formuláře a tam se napojit na db nebo se začít bavit s db tady
        // dotaz na vrácení všech služeb z tabulky service, které odpovídají těmto službám.
        //potom až přesměrovat na další formulář
    }
}