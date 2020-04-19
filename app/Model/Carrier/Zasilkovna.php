<?php

namespace LuTauch\App\Model\Carrier;

use LuTauch\App\Model\ICarrier;
use LuTauch\App\Model\PacketItems\Packet;
use SoapClient;
use Tracy\Debugger;

class Zasilkovna implements ICarrier
{

    public function processOrder(string $service, Packet $packet, string $location)
    {



        return TRUE;
    }


}