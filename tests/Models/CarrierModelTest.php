<?php

namespace LuTauch\tests\Forms;

use LuTauch\App\Model\Repository\OptionsModel;
use Tester\Assert;
use Tester\TestCase;

$container = require_once __DIR__ . '/../Bootstrap.php';

/**
 * @testCase CarrierModelTest
 */
class CarrierModelTest extends TestCase
{
    /**
     * @var OptionsModel
     */
    private $optionsModel;

    public function setUp()
    {
        $this->optionsModel = new OptionsModel();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->optionsModel = null;
    }


    public function testGetOptionsGroupSQLStringAllValues()
    {
        $values = $this->getValues(["packet_s", "packet_m", "packet_l", "packet_xl"], ["address_delivery", "pickup_delivery"], ["cash_on_delivery", "weekend_delivery", "evening_delivery"]);
        $res = $this->optionsModel->getOptionsGroupSQLString($values);
        $expected = '(packet_s = 1 OR packet_m = 1 OR packet_l = 1 OR packet_xl = 1) AND (address_delivery = 1 OR pickup_delivery = 1) AND (cash_on_delivery = 1 OR weekend_delivery = 1 OR evening_delivery = 1)';

        Assert::equal($expected, $res);
    }

    public function testGetOptionsGroupSQLStringGroup1()
    {
        $values = $this->getValues(["packet_s"]);
        $res = $this->optionsModel->getOptionsGroupSQLString($values);
        $expected = '(packet_s = 1)';

        Assert::equal($expected, $res);
    }

    public function testGetOptionsGroupSQLStringGroup2()
    {
        $values = $this->getValues([],["address_delivery"],[]);
        $res = $this->optionsModel->getOptionsGroupSQLString($values);
        $expected = '(address_delivery = 1)';

        Assert::equal($expected, $res);
    }

    public function testGetOptionsGroupSQLStringGroup3()
    {
        $values = $this->getValues([],[],["evening_delivery"]);
        $res = $this->optionsModel->getOptionsGroupSQLString($values);
        $expected = '(evening_delivery = 1)';

        Assert::equal($expected, $res);
    }

   public function testGetOptionsGroupSQLStringEmptyValues()
    {
        $values = $this->getValues();
        $res = $this->optionsModel->getOptionsGroupSQLString($values);
        $expected = '';

       Assert::equal($expected, $res);
    }

    private function getValues(array $packet = [], array $deliveryOption = [], array $additionalService = [])
    {
        $values = new \stdClass();
        $values->packet = $packet;
        $values->deliveryOption = $deliveryOption;
        $values->additionalService = $additionalService;

        return $values;
    }
}


$test = new CarrierModelTest();
$test->run();