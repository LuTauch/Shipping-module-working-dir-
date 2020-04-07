<?php

namespace LuTauch\App\Model\Carrier;

use LuTauch\App\Model\ICarrier;
use LuTauch\App\Model\PacketItems\Packet;

class Ulozenka implements ICarrier
{

    public function processOrder($service, Packet $packet)
    {
        return TRUE;
    }
}