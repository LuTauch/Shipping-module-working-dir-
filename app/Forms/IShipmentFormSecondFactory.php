<?php


namespace LuTauch\App\Forms;


interface IShipmentFormSecondFactory
{
    /**
     * @param array $serviceIds
     * @return ShipmentFormSecond
     */
    public function create();

}