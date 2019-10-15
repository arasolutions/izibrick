<?php


namespace App\Firebrock\Command;

class ContactCommand
{
    private $email;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $content
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

}