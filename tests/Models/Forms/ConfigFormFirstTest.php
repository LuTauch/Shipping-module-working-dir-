<?php


namespace Models\Forms;


use App\Forms\ConfigFormFirst;
use App\Model\Factory\CarrierFactory;
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
        $this->carrierModel = \Mockery::mock(\App\Model\CarrierModel::class);
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