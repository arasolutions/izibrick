<?php


namespace App\Izibrick\Command;


class SiteOptionsCommand
{

    private $domain;
    private $newDomain;
    private $existingDomain;

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain): void
    {
        $this->domain = $domain;
    }

    /**
     * SiteOptionsCommand constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getNewDomain()
    {
        return $this->newDomain;
    }

    /**
     * @param mixed $newDomain
     */
    public function setNewDomain($newDomain): void
    {
        $this->newDomain = $newDomain;
    }

    /**
     * @return mixed
     */
    public function getExistingDomain()
    {
        return $this->existingDomain;
    }

    /**
     * @param mixed $existingDomain
     */
    public function setExistingDomain($existingDomain): void
    {
        $this->existingDomain = $existingDomain;
    }


}