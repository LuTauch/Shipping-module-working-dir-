<?php


namespace LuTauch\App\Enum;


use http\Exception\InvalidArgumentException;

class DeliveryEnum
{
    const WEEKEND_DELIVERY = "Víkendové doručení";
    const EVENING_DELIVERY = "Večerní doručení";
    const EXPRESS_DELIVERY = "Expresní doručení";


    /**
     * @param $serviceName
     * @return string
     */
    public static function getTranslation($serviceName) : string {
        switch ($serviceName) {
            case "weekend_delivery":
                return self::WEEKEND_DELIVERY;
            case "evening_delivery":
                return self::EVENING_DELIVERY;
            case "express_delivery":
                return self::EXPRESS_DELIVERY;
            default:
                throw new InvalidArgumentException();
        }
    }
}