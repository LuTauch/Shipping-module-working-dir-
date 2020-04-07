<?php

namespace LuTauch\App\Model\Carrier;

use LuTauch\App\Model\ICarrier;
use LuTauch\App\Model\PacketItems\Packet;
use Tracy\Debugger;


class Gls implements ICarrier
{

    public function processOrder($service, Packet $packet)
    {
        // TODO: Implement processOrder() method.
        return TRUE;
    }
}