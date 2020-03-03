<?php


namespace App\Model\Factory;


abstract class BaseCarrierFactory
{
    abstract function createCarrier($name);

}