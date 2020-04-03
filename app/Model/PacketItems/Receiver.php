<?php

namespace LuTauch\App\Model\PacketItems;
class Receiver
{
    private $name;
    private $surname;
    private $phoneNo;
    private $email;
    private $countryCode;
    private $zipCode;
    private $city;
    private $street;
    private $houseNo;

    const COUNTRYCODE = "CZ";

    public function __construct($name, $surname, $phoneNo, $email, $street, $houseNo, $city, $zipCode)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->phoneNo = $phoneNo;
        $this->email = $email;
        $this->street = $street;
        $this->houseNo = $houseNo;
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->countryCode = self::COUNTRYCODE;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @return mixed
     */
    public function getPhoneNo()
    {
        return $this->phoneNo;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return mixed
     */
    public function getHouseNo()
    {
        return $this->houseNo;
    }
}