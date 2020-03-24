<?php


namespace LuTauch\App\Model\Factory;


use LuTauch\App\Model\Carrier\Zasilkovna;
use LuTauch\App\Model\Carrier\CeskaPosta;
use LuTauch\App\Model\Carrier\Dpd;
use LuTauch\App\Model\Carrier\Ppl;


class CarrierFactory extends BaseCarrierFactory
{
    /**
     * @param string $name
     * @return CeskaPosta|Dpd|Ppl|Zasilkovna
     * @throws \NonExistingCarrierException
     */
    public function createCarrier($name)
    {
        switch ($name) {
            case "CeskaPosta":
                return new CeskaPosta();
            case "Dpd":
                return new Dpd();
            case "Ppl":
                return new Ppl();
            case "Zasilkovna":
                return new Zasilkovna();
            default:
                throw new \NonExistingCarrierException();
        }
    }
}