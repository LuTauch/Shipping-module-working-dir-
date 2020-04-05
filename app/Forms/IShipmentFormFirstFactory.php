<?php


namespace LuTauch\App\Forms;


interface IShipmentFormFirstFactory
{
    /**
     * @param array $serviceIds
     * @return ShipmentFormFirst
     */
    public function create();

}