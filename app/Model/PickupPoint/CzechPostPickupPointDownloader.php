<?php


namespace LuTauch\App\Model\PickupPoint;


use Tracy\Debugger;
use Tracy\ILogger;

class CzechPostPickupPointDownloader
{
    const POST_OFFICE_XML_FEED = 'http://napostu.cpost.cz/vystupy/napostu.xml';

    public function download($downloadLocation)
    {
        $xmlFeedString = file_get_contents(self::POST_OFFICE_XML_FEED);
        if (!$xmlFeedString)
        {
            Debugger::log('Nepodařilo se stáhnout xml feed poboček české pošty z ' . self::POST_OFFICE_XML_FEED, ILogger::ERROR);
            return FALSE;
        }

        $saveResult = file_put_contents($downloadLocation, $xmlFeedString);
        if (!$saveResult)
        {
            Debugger::log('Nepodařilo se uložit xml feed české pošty', ILogger::ERROR);
            return FALSE;
        }

        return TRUE;
    }
}