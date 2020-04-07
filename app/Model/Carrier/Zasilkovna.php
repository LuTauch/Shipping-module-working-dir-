<?php

namespace LuTauch\App\Model\Carrier;

use LuTauch\App\Model\ICarrier;
use LuTauch\App\Model\PacketItems\Packet;

class Zasilkovna implements ICarrier
{

    public function processOrder($service, Packet $packet)
    {
        return TRUE;

    }
}