<?php


namespace LuTauch\App\Model\PickupPoint;

use Nette\InvalidArgumentException;
use Tracy\Debugger;

class CzechPostPickupPoint
{
    /** lokální kopie xml feedu pro zpracování xml feedu
     * const POST_OFFICE_TEMP_FILE_NA_POSTU = 'C:\xampp\htdocs\eshop\vendor\lu-tauch\shipping-module\temp\napostu.xml';
     * const POST_OFFICE_TEMP_FILE_DO_BALIKOVNY = 'C:\xampp\htdocs\eshop\vendor\lu-tauch\shipping-module\temp\dobalikovny.xml';
     **/

    const POST_OFFICE_XML_FEED_NA_POSTU = 'http://napostu.cpost.cz/vystupy/napostu.xml';
    const POST_OFFICE_XML_FEED_DO_BALIKOVNY = 'http://napostu.cpost.cz/vystupy/balikovny.xml';

    const NA_POSTU = 'Na poštu';
    const DO_BALIKOVNY = 'Do balíkovny';

    private $pickupPointDownloader;
    private $pickupPointParser;
    private $czechPostNaPostuRepository;

    private $service;
    /**
     * @var CzechPostDoBalikovnyRepository
     */
    private $czechPostDoBalikovnyRepository;

    /**
     * CzechPostPickupPoint constructor.
     * @param CzechPostPickupPointDownloader $pickupPointDownloader
     * @param CzechPostPickupPointParser $czechPointPickupPointParser
     * @param CzechPostNaPostuRepository $czechPostNaPostuRepository
     */
    public function __construct(
        CzechPostPickupPointDownloader $pickupPointDownloader,
        CzechPostPickupPointParser $czechPointPickupPointParser,
        CzechPostNaPostuRepository $czechPostNaPostuRepository,
        CzechPostDoBalikovnyRepository $czechPostDoBalikovnyRepository
    )
    {
        $this->pickupPointDownloader = $pickupPointDownloader;
        $this->pickupPointParser = $czechPointPickupPointParser;
        $this->czechPostNaPostuRepository = $czechPostNaPostuRepository;
        $this->czechPostDoBalikovnyRepository = $czechPostDoBalikovnyRepository;
    }

    /**
     * Hlavní metoda pro synchronizaci údajů o pobočkách české pošty.
     *
     * @param string $service 'Na poštu' or 'Do balíkovny'
     * @param string $downloadTo download location for xml file
     * @return void
     */
    public function synchronizePostOfficeData(string $service, string $downloadTo)
    {

        $downloadFrom = $this->getService($service);

        $success = $this->pickupPointDownloader->download($downloadFrom, $downloadTo);

        if (!$success) {
            Debugger::log('Download: Problém se synchronizací dat. (' . $downloadFrom . ')');
            return;
        }

        $xmlRootObject = $this->pickupPointParser->parse($downloadTo);
        if (empty($xmlRootObject)) {
            Debugger::log('Parse: Problém se synchronizací dat.' . '(' . $downloadFrom . ')');
            return;
        }

        if ($service == self::NA_POSTU) {
            $success = $this->czechPostNaPostuRepository->saveXml($xmlRootObject);
        } elseif ($service == self::DO_BALIKOVNY) {
            $success = $this->czechPostDoBalikovnyRepository->saveXml($xmlRootObject);
        }

        if (!$success) {
            Debugger::log('Save Xml: Problém se synchronizací dat.');
            return;
        }
        return;
    }

    /**
     * @param string $service
     * @return string
     */
    private function getService(string $service)
    {
        if ($service == self::NA_POSTU) {
           return self::POST_OFFICE_XML_FEED_NA_POSTU;
        } elseif ($service == self::DO_BALIKOVNY) {
            return self::POST_OFFICE_XML_FEED_DO_BALIKOVNY;
        } else {
            throw new InvalidArgumentException();
        }
    }

}