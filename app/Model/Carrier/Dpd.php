<?php

namespace App\Model\Carrier;

use App\Model\ICarrier;
use App\Model\PacketItems\Packet;

class Dpd implements ICarrier
{

    public function processOrder($service, Packet $packet)
    {
        // TODO: Implement processOrder() method.

    }
}