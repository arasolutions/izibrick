<?php


namespace App\Izibrick\Command;


class SiteOptionsCommand
{

    private $domain;

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
}