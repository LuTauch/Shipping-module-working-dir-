<?php


namespace models\Factory;


use App\Model\Factory\CarrierFactory;
use Tester\TestCase;
use Tester\Assert;

/**
 * @testCase CarrierFactoryTest
 */

$container = require_once __DIR__ . '/../../Bootstrap.php';


class CarrierFactoryTest extends TestCase
{

    /** @var CarrierFactory */
    private $carrierFactory;

    public function setUp()
    {
        $this->carrierFactory = new CarrierFactory();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->carrierFactory = null;
    }

    /**
     *
     */
    public function testCreateExistingCarrier()
    {
        $carrier = "CeskaPosta";
        $res = $this->carrierFactory->createCarrier($carrier);

        Assert::type('App\Model\Carrier\CeskaPosta', $res);
    }

    /**
     * @throws \NonexistingCarrierException
     */
    public function testCreateNonExistingCarrier()
    {
        $carrier = "NonExistingCarrier";

        Assert::exception($this->carrierFactory->createCarrier($carrier), \NonExistingCarrierException::class);
    }
}

$testCase = new CarrierFactoryTest();
$testCase->run();
