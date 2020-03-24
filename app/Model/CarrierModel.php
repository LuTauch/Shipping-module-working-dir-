<?php


namespace LuTauch\App\Model;


use Nette\Database\Context;
use LuTauch\App\Model\OptionsModel;
use Tracy\Debugger;

/**
 * Class CarrierModel serves for database connection. It contains methods mainly for getting data from database.
 * It extends class BaseModel.
 */
class CarrierModel extends BaseModel
{
    /** @var string name of the main table in the database */
    const TABLE_NAME = "service";

    /** @var OptionsModel */
    protected $optionsModel;

    /**
     * CarrierModel constructor.
     * @param Context $database
     * @param \App\Model\OptionsModel $optionsModel
     */
    public function __construct(Context $database, OptionsModel $optionsModel)
    {
        parent::__construct($database);
        $this->optionsModel = $optionsModel;
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
     * @param $serviceIds
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

        $innerSql = $this->optionsModel->getOptionsGroupSQLString($values);

        $serviceIds = $this->database->query('SELECT service_id FROM service WHERE ' . $innerSql)->fetchPairs(NULL, 'service_id');

        return $serviceIds;
    }

    /**
     * Gets service ids which suit to the condition given by sql query $innerSql.
     * @param stdClass $values
     * @return array
     */
    public function findServiceIdsFromSelected($values)
    {
        $innerSql = $this->optionsModel->getOptionsGroupSQLString($values);

        $serviceIds = $this->database->query('SELECT service_id FROM service WHERE ' . $innerSql . ' AND  selected = 1')->fetchPairs(NULL, 'service_id');

        return $serviceIds;
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