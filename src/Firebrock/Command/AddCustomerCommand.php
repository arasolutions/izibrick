<?php


namespace App\Firebrock\Command;


use App\Entity\Product;

class AddCustomerCommand
{
    private $managerLastName;
    private $managerFirstName;
    private $managerPhone;
    private $managerMail;

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


}