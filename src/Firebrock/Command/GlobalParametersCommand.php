<?php


namespace App\Firebrock\Command;

use App\Entity\Product;
use App\Entity\Site;

class GlobalParametersCommand
{
    private $name;
    private $domain;
    private $logo;
    private $keys;
    private $favicon;
    private $facebook;
    private $twitter;
    private $instagram;
    private $template;

    /**
     * GlobalParametersCommand constructor.
     * @param Site $site
     */
    public function __construct(Site $site)
    {
        $this->name = $site->getName();
        $this->domain = $site->getDomain();
        $this->instagram = $site->getInstagram();
        $this->facebook=$site->getFacebook();
        $this->keys=$site->getKeyWords();
        $this->twitter=$site->getTwitter();
        $this->logo=$site->getLogo();
        $this->template=$site->getTemplate();
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
     * @return null|string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param null|string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo): void
    {
        $this->logo = $logo;
    }

    /**
     * @return mixed
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * @param mixed $keys
     */
    public function setKeys($keys): void
    {
        $this->keys = $keys;
    }

    /**
     * @return mixed
     */
    public function getFavicon()
    {
        return $this->favicon;
    }

    /**
     * @param mixed $favicon
     */
    public function setFavicon($favicon): void
    {
        $this->favicon = $favicon;
    }

    /**
     * @return mixed
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param mixed $facebook
     */
    public function setFacebook($facebook): void
    {
        $this->facebook = $facebook;
    }

    /**
     * @return mixed
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param mixed $twitter
     */
    public function setTwitter($twitter): void
    {
        $this->twitter = $twitter;
    }

    /**
     * @return mixed
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * @param mixed $instagram
     */
    public function setInstagram($instagram): void
    {
        $this->instagram = $instagram;
    }

    /**
     * @return \App\Entity\Template|null
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param \App\Entity\Template|null $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

}