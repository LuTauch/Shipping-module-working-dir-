<?php


namespace LuTauch\App\Model\PickupPoint;

use Tracy\Debugger;
use Tracy\ILogger;

class CzechPostPickupPointParser
{
    public function parse($downloadLocation)
    {
        // parsování staženého xml feedu
        libxml_use_internal_errors(TRUE);
        $xmlRootObject = simplexml_load_file($downloadLocation);

        if (FALSE === $xmlRootObject)
        {
            Debugger::log('Při parsování xml feedu poboček české pošty došlo k následujícím chybám:', ILogger::ERROR);
            foreach (libxml_get_errors() as $error)
            {
                Debugger::log($error->message);
            }
            return null;
        }

        return $xmlRootObject;
    }
}