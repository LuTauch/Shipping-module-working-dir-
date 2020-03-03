<?php

interface ICarrier {
    public function processOrder($id, Carrier $service, Packet $packet);

}
