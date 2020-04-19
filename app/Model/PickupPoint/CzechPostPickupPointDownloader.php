<?php


namespace LuTauch\App\Model\PickupPoint;


use Tracy\Debugger;
use Tracy\ILogger;

class CzechPostPickupPointDownloader
{


    public function download($from, $to)
    {
        $xmlFeedString = file_get_contents($from);
        if (!$xmlFeedString)
        {
            Debugger::log('Nepodařilo se stáhnout xml feed poboček české pošty z ' . $from
                . ILogger::ERROR);
            return FALSE;
        }

        $saveResult = file_put_contents($to, $xmlFeedString);
        if (!$saveResult)
        {
            Debugger::log('Nepodařilo se uložit xml feed české pošty', ILogger::ERROR);
            return FALSE;
        }

        return TRUE;
    }
}