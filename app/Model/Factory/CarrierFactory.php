<?php


namespace LuTauch\App\Model\Factory;


use LuTauch\App\Model\Carrier\Zasilkovna;
use LuTauch\App\Model\Carrier\CeskaPosta;
use LuTauch\App\Model\Carrier\Dpd;
use LuTauch\App\Model\Carrier\Ppl;


class CarrierFactory extends BaseCarrierFactory
{
    /**
     * @param $name
     * @return CeskaPosta|Dpd|Ppl|Zasilkovna
     * @throws \NonExistingCarrierException
     */
    public function createCarrier($name)
    {
        switch ($name) {
            case "Česká pošta":
                return new CeskaPosta();
            case "DPD":
                return new Dpd();
            case "PPL":
                return new Ppl();
            case "Zásilkovna":
                return new Zasilkovna();
            default:
                throw new \NonExistingCarrierException();
        }
    }
}