<?php

namespace App\Model;
use App\Model\PacketItems\Packet;

interface ICarrier {
    public function processOrder($service, Packet $packet);

}
