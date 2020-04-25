<?php


namespace LuTauch\App\Model\PickupPoint;

use LuTauch\App\Model\PickupPoint\ZasilkovnaPickupPointDownload;
use LuTauch\App\Model\PickupPoint\ZasilkovnaPickupPointRepository;
use Tracy\Debugger;

class ZasilkovnaPickupPoint
{
    /** lokální kopie xml feedu pro zpracování xml feedu */
    /**
     * @var ZasilkovnaPickupPointDownload
     */
    private $zasilkovnaPickupPointDownload;
    /**
     * @var ZasilkovnaPickupPointRepository
     */
    private $zasilkovnaPickupPointRepository;

    public function __construct(ZasilkovnaPickupPointDownload $zasilkovnaPickupPointDownload,
                                ZasilkovnaPickupPointRepository $zasilkovnaPickupPointRepository
    )
    {
        $this->zasilkovnaPickupPointDownload = $zasilkovnaPickupPointDownload;
        $this->zasilkovnaPickupPointRepository = $zasilkovnaPickupPointRepository;
    }

    /**
     * Hlavní metoda pro synchronizaci údajů o pobočkách české pošty.
     *
     * @return void
     */
    public function synchronizePostOfficeData()
    {
        $branches = $this->zasilkovnaPickupPointDownload->getBranches();
        if (!$branches) {
            Debugger::log('Problém se synchronizací dat.');
            return;
        }


        $success = $this->zasilkovnaPickupPointRepository->saveData($branches);
        if (!$success) {
            Debugger::log('Problém se synchronizací dat.');
            return;
        }
        return;
    }

}