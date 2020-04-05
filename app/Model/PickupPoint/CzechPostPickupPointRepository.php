<?php


namespace LuTauch\App\Model\PickupPoint;

use LuTauch\app\Model\Repository\Repository;
use Tracy\Debugger;

class CzechPostPickupPointRepository extends Repository
{

    public function saveXml($xmlRootObject)
    {
        if ($xmlRootObject == null) {
            return FALSE;
        }

        try {
            $this->database->beginTransaction();

            // smazání původního obsahu tabulky
            $this->findAll()->delete();

            // import nového obsahu z načteného xml feedu
            foreach ($xmlRootObject->row as $postOfficeRecord) {
                // $this->processPostOfficeRecord($postOfficeRecord);

                // sestavení kompletního záznamu řádku tabulky
                $dataRow = [
                    'psc' => $postOfficeRecord->PSC,
                    'nazev' => $postOfficeRecord->NAZ_PROV,
                    'okres' => $postOfficeRecord->OKRES,
                    'adresa' => $postOfficeRecord->ADRESA,
                    'info' => $postOfficeRecord->OTV_DOBA
                ];

                $this->insert($dataRow);

            }

            $this->database->commit();
        } catch (\Nette\Database\DriverException $ex) {
            Debugger::log('Import xml feedu poboček češké pošty selhal s chybou: ' . $ex->getMessage());
            $this->database->rollBack();
            throw $ex;
        }

        return TRUE;
    }

    protected function getTableName()
    {
        return 'ceska_posta';
    }

    public function findByZip($zip)
    {
        $by = [
            'psc LIKE ?' => $zip . '%'
        ];

        return $this->findBy($by);
    }


}