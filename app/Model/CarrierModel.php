<?php


namespace App\Model;


use Nette;
use Nette\Application\UI;
use Nette\Forms\Form;
use stdClass;
use Tracy\Debugger;

/**
 * Class CarrierModel serves for database connection. It contains methods mainly for getting data from database.
 * It extends class BaseModel.
 */
class CarrierModel extends BaseModel
{
    /** @var string name of the main table in the database */
    const TABLE_NAME = "service";

    /**
     * CarrierModel constructor.
     * @param Nette\Database\Context $database
     */
    public function __construct(Nette\Database\Context $database)
    {
        parent::__construct($database);
    }

    /**
     * Gets table name given by a constant TABLE_NAME.
     * @return string
     */
    public function getTableName()
    {
        return self::TABLE_NAME;
    }

    /**
     * Gets names of carrier services by their ids.
     * @param $serviceIds service ids
     * @return array|Nette\Database\IRow[] service ids paired with their names
     */
    public function getServicesForCheckboxList($serviceIds)
    {

        $sql = 'SELECT service_id, CONCAT(carrier_name, " - ", service_name) AS complete_name 
                FROM carrier
                INNER JOIN service ON service.carrier_id = carrier.carrier_id
                WHERE service_id IN (?)';

        $params = [$serviceIds];

        return $this->database->queryArgs($sql, $params)->fetchPairs('service_id', 'complete_name');
    }

    /**
     * Gets service ids which suit to the condition given by sql query $innerSql.
     * @param stdClass $values
     * @return array
     */
    public function findServiceIds($values)
    {
        $innerSql = $this->getOptionsGroupSQLString($values);

        $serviceIds = $this->database->query('SELECT service_id FROM service WHERE ' . $innerSql)->fetchPairs(NULL, 'service_id');

        return $serviceIds;
    }

    /**
     * Creates sql query by iterating values sent from the user in parameter and adding key word AND/OR between them.
     * Each part of the query represents one option group (e.g. packet size, delivery type, etc.). Parts are connected by AND.
     * The inner values in each part represents specific options (e.g. packet_s, weekend delivery, etc.) and those are connected by OR.
     * @param stdClass $values
     * @return string
     */
    private function getOptionsGroupSQLString($values)
    {
        $innerSql = '';

        //iterating over the option groups
        foreach ($values as $optionGroup) {
            //skipping an empty option group
            if (!$optionGroup) {
                continue;
            }

            $innerSql .= '(';

            $optionGroupCount = count($optionGroup);

            //iterating over the values from each option group
            foreach ($optionGroup as $innerIteratorKey => $item) {
                //setting value 1 for each
                $innerSql .= $item . ' = 1';

                //setting OR to the condition
                if ($innerIteratorKey !== $optionGroupCount - 1) {
                    $innerSql .= ' OR ';
                }
            }

            $innerSql .= ') AND ';
        }

        $innerSql = substr($innerSql, 0, -4);

        return $innerSql;
    }

    /**
     * Updates column 'selected' in the table 'service'
     * @param $serviceIds
     * @return int
     */
    public function saveSelectedServiceIds($serviceIds)
    {
        $by = [
            'service_id' => $serviceIds
        ];

        $updateData = [
            'selected' => 1
        ];

        return $this->findBy($by)->update($updateData);
    }


}