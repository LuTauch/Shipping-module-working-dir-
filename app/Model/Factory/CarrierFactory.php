<?php


namespace App\Model\Factory;


use App\Model\Carrier\Zasilkovna;
use App\Model\Carrier\CeskaPosta;
use App\Model\Carrier\Dpd;
use App\Model\Carrier\Ppl;


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