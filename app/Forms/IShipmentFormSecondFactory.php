<?php


namespace LuTauch\App\Forms;


interface IShipmentFormSecondFactory
{
    /**
     * @return ShipmentFormFirst
     */
    public function create();

}