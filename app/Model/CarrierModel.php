<?php


namespace LuTauch\App\Model;


use LuTauch\App\Model\OptionsModel;
use Nette\Database\Context;
use LuTauch\App\Model\Options;
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
     * @param \LuTauch\App\Model\OptionsModel $optionsModel
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
        return $this->getServicesByIds($serviceIds)->fetchPairs('service_id', 'complete_name');
    }

    public function getServicesByIds($serviceIds)
    {
        $sql = 'SELECT service_id, CONCAT(carrier_name, " - ", service_name) AS complete_name, price
                FROM carrier
                INNER JOIN service ON service.carrier_id = carrier.carrier_id
                WHERE service_id IN (?)';

        $params = [$serviceIds];

        return $this->database->queryArgs($sql, $params);
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
    public function findServiceIdsFromSelected($weight)
    {
        /* $innerSql = $this->optionsModel->getOptionsGroupSQLString($values);

        if ($innerSql != '') {
            $innerSql = $innerSql . ' AND ';
        }
        $packetCategorySql = $this->optionsModel->getCategoryOfPacket($weight);
        */

        $serviceIds = $this->database->query('SELECT service_id FROM service WHERE selected = 1 AND max_weight > ?', $weight)->fetchPairs(NULL, 'service_id');
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

    public function getPriceOfService($id)
    {
        return $this->database->query('SELECT price FROM service WHERE service_id = ?', $id)->fetchPairs(NULL, 'price');
    }

    public function getServicesWithPickup()
    {
        $by = [
            'has_pickup' => 1
        ];

        return $this->findBy($by);
    }

    /**
     * Updates column 'selected' in the table 'service'
     * @return int
     */
    public function resetSelectedServiceIds()
    {
        $by = [
            'selected' => 1
        ];

        $updateData = [
            'selected' => 0
        ];

        return $this->findBy($by)->update($updateData);
    }

    public function findPacketType()
    {
        $by = [
            'selected' => 1
        ];

        $res = $this->findBy($by)->fetchAll();
        return $res;
    }


}