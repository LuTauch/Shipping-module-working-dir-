<?php


namespace LuTauch\App\Model\PickupPoint;

use LuTauch\app\Model\Repository\ZasilkovnaPickUpPointDownload;
use LuTauch\app\Model\Repository\ZasilkovnaPickupPointRepository;
use Tracy\Debugger;

class ZasilkovnaPickupPoint
{
    /** lokální kopie xml feedu pro zpracování xml feedu */
    /**
     * @var ZasilkovnaPickupPointDownload
     */
    private $zasilkovnaPickUpPointDownload;
    /**
     * @var ZasilkovnaPickupPointRepository
     */
    private $zasilkovnaPickupPointRepository;

    public function __construct(ZasilkovnaPickUpPointDownload $zasilkovnaPickUpPointDownload,
                                ZasilkovnaPickupPointRepository $zasilkovnaPickupPointRepository
    )
    {
        $this->zasilkovnaPickUpPointDownload = $zasilkovnaPickUpPointDownload;
        $this->zasilkovnaPickupPointRepository = $zasilkovnaPickupPointRepository;
    }

    /**
     * Hlavní metoda pro synchronizaci údajů o pobočkách české pošty.
     *
     * @return void
     */
    public function synchronizePostOfficeData()
    {
        $branches = $this->zasilkovnaPickUpPointDownload->getBranches();
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