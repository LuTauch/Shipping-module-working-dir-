<?php

use Nette\Application\UI;

class ConfigFormSecond extends UI\Control
{

protected function createComponentConfigFormCarriers() :UI\Form {
    $form = new UI\Form();
    //použít low level formulář?
    return $form;
}
}