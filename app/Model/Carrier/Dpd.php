<?php

namespace LuTauch\App\Model\Carrier;

use LuTauch\App\Model\ICarrier;
use LuTauch\App\Model\PacketItems\Packet;

class Dpd implements ICarrier
{

    public function processOrder(string $service, Packet $packet, string $location)
    {
      return TRUE;

    }
}