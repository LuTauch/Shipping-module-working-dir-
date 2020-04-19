<?php


namespace LuTauch\App\Model\PickupPoint;

use LuTauch\App\Model\BaseModel;
use Tracy\Debugger;

class CzechPostNaPostuRepository extends BaseModel
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
        return 'ceska_posta_na_postu';
    }

    public function findByZip($zip)
    {
        $by = [
            'psc LIKE ?' => $zip . '%'
        ];

        return $this->findBy($by);
    }

    public function findByCity($address)
    {
        $by = [
            'adresa LIKE ?' => '%' . $address . '%'
        ];

        return $this->findBy($by);
    }

    public function filterAddressByZip($zip) {
        $result = [];

        $data = $this->findByZip($zip)->limit(15)->fetchAll();

        if (!empty($data)) {
            foreach ($data as $row) {
                $result[] = [
                    'id' => $row->psc,
                    'adresa' => $row->adresa,
                ];
            }
        }

        return $result;
    }

    public function filterAddressByCity($city) {
        $result = [];

        $data = $this->findByCity($city)->limit(15)->fetchAll();

        if (!empty($data)) {
            foreach ($data as $row) {
                $result[] = [
                    'psc' => $row->psc,
                    'adresa' => $row->adresa,
                ];
            }
        }

        return $result;
    }

}