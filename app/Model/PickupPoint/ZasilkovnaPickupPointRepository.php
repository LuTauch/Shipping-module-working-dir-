<?php


namespace LuTauch\App\Model\PickupPoint;


use LuTauch\App\Model\Repository\BaseModel;
use Tracy\Debugger;

class ZasilkovnaPickupPointRepository extends BaseModel
{

    public function saveData($data)
    {
        if (empty($data)) {
            return FALSE;
        }

        try {
            $this->database->beginTransaction();

            // smazání původního obsahu tabulky
            $this->findAll()->delete();

            // import nového obsahu z načteného xml feedu
            foreach ($data as $row) {
                // sestavení kompletního záznamu řádku tabulky
                $dataRow = [
                    'id' => $row->id,
                    'title' => isset($row->title) ? $row->title : $row->place,
                    'city' => $row->city,
                    'street' => $row->street,
                    'zip' => $row->zip,
                    'directions' => isset($row->directions) ? $row->directions : null,
                    'zasilkovna_url' => isset($row->zasilkovna_url) ? $row->zasilkovna_url : $row->url,
                    'country' => $row->country,
                    'active' => isset($row->active) ? $row->active : 1,
                ];

                //throw new \Exception('prusvih!');

                $this->insert($dataRow);

            }

            $this->database->commit();
        } catch (\Nette\Database\DriverException $ex) {
            Debugger::log('Import xml feedu poboček Zásilkovny selhal s chybou: ' . $ex->getMessage());
            $this->database->rollBack();
            throw $ex;
        }

        return TRUE;
    }

    protected function getTableName()
    {
        return 'zasilkovna';
    }


    public function findByAddress($address)
    {
        $by = [
            'city LIKE ? OR zip LIKE ? OR street LIKE ?' => ['%' . $address . '%', '%' . $address . '%', '%' . $address . '%']
        ];

        return $this->findBy($by);
    }

    public function filterAddress($address) {
        $result = [];

        $data = $this->findByAddress($address)->limit(15)->fetchAll();

        if (!empty($data)) {
            foreach ($data as $row) {
                $result[] = [
                    'id' => $row->id,
                    'adresa' => $row->street . ', ' . $row->city . ', ' . $row->zip,
                ];
            }
        }

        return $result;
    }
}

