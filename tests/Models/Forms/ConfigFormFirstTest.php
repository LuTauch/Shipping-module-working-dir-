<?php


namespace LuTauch\tests\Models\Forms;


use LuTauch\App\Forms\ConfigFormFirst;
use LuTauch\App\Model\Repository\CarrierModel;
use Tester\Assert;
use Tester\TestCase;

$container = require_once __DIR__ . '/../../Bootstrap.php';

class ConfigFormFirstTest extends TestCase
{
    private $carrierModel;

    /** @var ConfigFormFirst */
    private $configFormFirst;

    public function __construct()
    {
        $this->carrierModel = \Mockery::mock(CarrierModel::class);
    }

    public function setUp()
    {
        $this->configFormFirst = new ConfigFormFirst($this->carrierModel);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->configFormFirst = null;
    }

    /**
     *
     * @throws \Exception
     */
    public function testCreateConfigFormFirst()
    {
        $res = new ConfigFormFirst($this->carrierModel);
        $form = $res->createComponentForm();

        Assert::type('Nette\Application\UI\Form', $form);
    }

}

$test = new ConfigFormFirstTest();
$test->run();