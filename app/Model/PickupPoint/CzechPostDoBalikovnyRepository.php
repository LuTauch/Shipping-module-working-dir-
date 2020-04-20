<?php


namespace LuTauch\App\Model\PickupPoint;

use LuTauch\App\Model\BaseModel;
use Tracy\Debugger;

class CzechPostDoBalikovnyRepository extends BaseModel
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
                    'nazev' => $postOfficeRecord->NAZEV,
                    'obec' => $postOfficeRecord->OBEC,
                    'cast_obce' => $postOfficeRecord->C_OBCE,
                    'adresa' => $postOfficeRecord->ADRESA,
                    'info' => $postOfficeRecord->OTV_DOBA
                ];

                $this->insert($dataRow);

            }

            $this->database->commit();
        } catch (\Nette\Database\DriverException $ex) {
            Debugger::log('Save xml - do balikovny: Import xml feedu poboček češké pošty selhal s chybou: ' . $ex->getMessage());
            $this->database->rollBack();
            throw $ex;
        }

        return TRUE;
    }

    protected function getTableName()
    {
        return 'ceska_posta_do_balikovny';
    }

    public function findByAddress($address)
    {
        $by = [
            'adresa LIKE ?' => '%' . $address . '%'
        ];

        return $this->findBy($by);
    }


    public function filterAddress($address) {
        $result = [];

        $data = $this->findByAddress($address)->limit(15)->fetchAll();

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