<?php

namespace LuTauch\App\Forms;

use Nette\Application\UI\Control;

/**
 * Class BaseComponent represents a base component of the project. It contains only a method for content rendering.
 * @package App\Forms
 */
abstract class BaseComponent extends Control
{
    /**
     * Gets name for template (.latte) from class name (.php) and renders the template.
     */
    public function render()
    {
        $componentFileName = $this->getReflection()->getFileName();
        $latteFileName = str_replace(".php", ".latte", $componentFileName);
        $this->template->setFile($latteFileName);
        $this->template->render();
    }
}