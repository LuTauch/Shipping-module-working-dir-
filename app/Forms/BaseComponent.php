<?php

namespace App\Forms;

use Nette\Application\UI\Control;

abstract class BaseComponent extends Control
{
    /**
     * ziskame nazev pro latte z nazvu tridy php
     */
    public function render()
    {
        $componentFileName = $this->getReflection()->getFileName();
        $latteFileName = str_replace(".php", ".latte", $componentFileName);
        $this->template->setFile($latteFileName);
        $this->template->render();
    }
}