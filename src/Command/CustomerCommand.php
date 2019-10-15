<?php


namespace App\Command;


use App\Entity\Product;

class CustomerCommand
{
    private $businessName;
    private $address;
    private $address2;
    private $postCode;
    private $city;
    private $country;
    private $managerLastName;
    private $managerFirstName;
    private $managerPhone;
    private $managerMail;

    /**
     * @return mixed
     */
    public function getManagerMail()
    {
        return $this->managerMail;
    }

    /**
     * @param mixed $managerMail
     */
    public function setManagerMail($managerMail): void
    {
        $this->managerMail = $managerMail;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getBusinessName()
    {
        return $this->businessName;
    }

    /**
     * @param mixed $businessName
     */
    public function setBusinessName($businessName): void
    {
        $this->businessName = $businessName;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param mixed $address2
     */
    public function setAddress2($address2): void
    {
        $this->address2 = $address2;
    }

    /**
     * @return mixed
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @param mixed $postCode
     */
    public function setPostCode($postCode): void
    {
        $this->postCode = $postCode;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getManagerLastName()
    {
        return $this->managerLastName;
    }

    /**
     * @param mixed $managerLastName
     */
    public function setManagerLastName($managerLastName): void
    {
        $this->managerLastName = $managerLastName;
    }

    /**
     * @return mixed
     */
    public function getManagerFirstName()
    {
        return $this->managerFirstName;
    }

    /**
     * @param mixed $managerFirstName
     */
    public function setManagerFirstName($managerFirstName): void
    {
        $this->managerFirstName = $managerFirstName;
    }

    /**
     * @return mixed
     */
    public function getManagerPhone()
    {
        return $this->managerPhone;
    }

    /**
     * @param mixed $managerPhone
     */
    public function setManagerPhone($managerPhone): void
    {
        $this->managerPhone = $managerPhone;
    }



}