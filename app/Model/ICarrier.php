<?php

namespace LuTauch\App\Model;
use LuTauch\App\Model\PacketItems\Packet;

interface ICarrier {
    public function processOrder(string $service, Packet $packet, string $location);

}
