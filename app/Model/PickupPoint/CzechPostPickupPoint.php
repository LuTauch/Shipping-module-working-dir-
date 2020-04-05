<?php


namespace LuTauch\App\Model\PickupPoint;

use Tracy\Debugger;

class CzechPostPickupPoint
{
    /** lokální kopie xml feedu pro zpracování xml feedu */
    //TODO - prepsat na absolutní path
    //const POST_OFFICE_TEMP_FILE = 'C:\xampp\htdocs\eshop\vendor\lu-tauch\shipping-module\temp/napostu.xml';
    const POST_OFFICE_TEMP_FILE = __DIR__ . '/../temp/napostu.xml';

    private $pickupPointDownloader;
    private $pickupPointParser;
    private $pickupPointRepository;
    private $downloadLocation;

    public function __construct(
        CzechPostPickupPointDownloader $pickupPointDownloader,
        CzechPostPickupPointParser $czechPointPickupPointParser,
        CzechPostPickupPointRepository $czechPointPickupPointRepository
    )
    {
        $this->pickupPointDownloader = $pickupPointDownloader;
        $this->pickupPointParser = $czechPointPickupPointParser;
        $this->pickupPointRepository = $czechPointPickupPointRepository;
        $this->downloadLocation = self::POST_OFFICE_TEMP_FILE;
    }

    /**
     * Hlavní metoda pro synchronizaci údajů o pobočkách české pošty.
     *
     * @return void
     */
    public function synchronizePostOfficeData()
    {
        $success = $this->pickupPointDownloader->download($this->downloadLocation);
        if (!$success) {
            Debugger::log('Problém se synchronizací dat.');
            return;
        }

        $xmlRootObject = $this->pickupPointParser->parse($this->downloadLocation);
        if (empty($xmlRootObject)) {
            Debugger::log('Problém se synchronizací dat.');
            return;
        }

        $success = $this->pickupPointRepository->saveXml($xmlRootObject);
        if (!$success) {
            Debugger::log('Problém se synchronizací dat.');
            return;
        }
        return;
    }

}