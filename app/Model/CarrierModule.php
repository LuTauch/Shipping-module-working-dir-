<?php

use Nette;

/**
 * Class Carrier_module bude využita pro práci s databází ->
 * bude tahat info z databáze a zpracovávat výstup, který bude vracet většinou do formulářů
 *
 */

class Carrier_module
{

    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }
}