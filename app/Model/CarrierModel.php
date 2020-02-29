<?php


namespace App\Model;


use Nette;
use Nette\Application\UI;
use Nette\Forms\Form;
use Tracy\Debugger;

/**
 * Class Carrier_module bude využita pro práci s databází ->
 * bude tahat info z databáze a zpracovávat výstup, který bude vracet většinou do formulářů
 *
 */

class CarrierModel extends BaseModel
{

    const TABLE_NAME = "service";

    public function __construct(Nette\Database\Context $database)
    {
        parent::__construct($database);
    }

    public function getTableName()
    {
        return self::TABLE_NAME;
    }

    /**
     * @param $serviceIds
     * @return array|Nette\Database\IRow[]
     */
   public function getServicesForCheckboxList($serviceIds){

       $sql = 'SELECT service_id, CONCAT(carrier_name, " - ", service_name) AS complete_name 
                FROM carrier
                INNER JOIN service ON service.carrier_id = carrier.carrier_id
                WHERE service_id IN (?)';

       $params = [$serviceIds];

       return $this->database->queryArgs($sql, $params)->fetchPairs('service_id', 'complete_name');
    }

    /**
     * @param $values
     * @return array
     */
    public function findServiceIds($values)
    { //typ argumentu pole? nebo kolekce?
        $innerSql = $this->getOptionsGroupSQLString($values);

        $serviceIds = $this->database->query('SELECT service_id FROM service WHERE '.$innerSql)->fetchPairs(NULL, 'service_id');

        return $serviceIds;
    }

    /**
     * @param $values
     * @return string
     */
    private function getOptionsGroupSQLString($values)
    {
        $innerSql = '';
        $optionsCount = count((array) $values);
        $iteratorKey = 0;

        foreach($values as $optionGroup) {
            if (!$optionGroup)
            {
                $iteratorKey++;
                continue;
            }

            $innerSql .= '(';

            $optionGroupCount = count($optionGroup);

            foreach ($optionGroup as $innerIteratorKey => $item) {
                $innerSql .= $item . ' = 1';

                if($innerIteratorKey !== $optionGroupCount-1) {
                    $innerSql .= ' OR ';
                }
            }

            $innerSql .= ')';

            if($iteratorKey !== $optionsCount-1) {
                $innerSql .= ' AND ';
            }

            $iteratorKey++;
        }

        return $innerSql;
    }


    public function saveSelectedServiceIds($serviceIds) {
        $by = [
            'service_id' => $serviceIds
        ];

        $updateData = [
          'selected' => 1
        ];

        return $this->findBy($by)->update($updateData);
    }

    public function showSelected(){
        //SELECT * FROM service WHERE selected = 1
        //fetchAll
    }


}