<?php


namespace App\Izibrick\Command;


use App\Entity\Product;

class AddCustomerCommand
{
    private $managerLastName;
    private $managerFirstName;
    private $managerPhone;
    private $email;
    private $plainPassword;

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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }


}