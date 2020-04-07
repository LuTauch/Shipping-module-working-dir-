<?php

namespace LuTauch\App\Model\Carrier;

use LuTauch\App\Model\ICarrier;
use LuTauch\App\Model\PacketItems\Packet;
use Tracy\Debugger;


class InTime implements ICarrier
{

    public function processOrder($service, Packet $packet)
    {
        // TODO: Implement processOrder() method.
        return TRUE;
    }
}