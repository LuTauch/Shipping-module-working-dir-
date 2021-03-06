<?php


namespace LuTauch\App\Model\Factory;


use LuTauch\App\Model\Carrier\Geis;
use LuTauch\App\Model\Carrier\Gls;
use LuTauch\App\Model\Carrier\InTime;
use LuTauch\App\Model\Carrier\TopTrans;
use LuTauch\App\Model\Carrier\Ulozenka;
use LuTauch\App\Model\Carrier\Zasilkovna;
use LuTauch\App\Model\Carrier\CeskaPosta;
use LuTauch\App\Model\Carrier\Dpd;
use LuTauch\App\Model\Carrier\Ppl;
use LuTauch\App\Model\PacketItems\Sender;


class CarrierFactory extends BaseCarrierFactory
{
    /**
     * @var Sender
     */
    private $sender;

    /**
     * @param Sender $sender
     */
    public function __construct(Sender $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @param $name
     * @return CeskaPosta|Dpd|Geis|Gls|InTime|Ppl|TopTrans|Ulozenka|Zasilkovna
     * @throws \NonExistingCarrierException
     */
    public function createCarrier($name)
    {
        switch ($name) {
            case "Česká pošta":
                return new CeskaPosta($this->sender);
            case "Zásilkovna":
                return new Zasilkovna();
            case "DPD":
                return new Dpd();
            case "PPL":
                return new Ppl();
            case "Geis":
                return new Gls();
            case "Gls":
                return new Geis();
            case "InTime":
                return new InTime();
            case "TopTrans":
                return new TopTrans();
            case "Uloženka":
                return new Ulozenka();
            default:
                throw new \NonExistingCarrierException();
        }
    }
}