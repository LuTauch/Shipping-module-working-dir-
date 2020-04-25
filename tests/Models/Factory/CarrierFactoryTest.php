<?php


namespace LuTauch\tests\Models\Factory;


use LuTauch\App\Model\Factory\CarrierFactory;
use LuTauch\App\Model\PacketItems\Sender;
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

    /**
     * @var Sender
     */
    private $sender;

    public function __construct()
    {
        $this->sender = new Sender('Lucie', '32000', 'Plzeň',
            'Plzeň-město', 'Solní', '1', '123456789',
            'lucie@tauchenova.com');
    }

    public function setUp()
    {
        $this->carrierFactory = new CarrierFactory($this->sender);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->carrierFactory = null;
    }

    /**
     * @throws \NonExistingCarrierException
     */
    public function testCreateExistingCarrier()
    {
        $carrier = "Česká Pošta";
        $res = $this->carrierFactory->createCarrier($carrier);

        Assert::type('LuTauch\App\Model\Carrier\CeskaPosta', $res);
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
