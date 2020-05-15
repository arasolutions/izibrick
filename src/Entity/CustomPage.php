<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomPageRepository")
 * @ORM\Table(name="fir_custom_page")
 */
class CustomPage
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="customPages", fetch="EAGER")
     */
    private $site;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default":1})
     */
    private $place;

    /**
     * @ORM\Column(type="text", length=128, nullable=true)
     */
    private $nameMenu;

    /**
     * @ORM\Column(type="text", length=128, nullable=true)
     */
    private $nameMenuUrl;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(name="seo_title", type="string", length=128, nullable=true)
     */
    private $seoTitle;

    /**
     * @ORM\Column(name="seo_description", type="string", length=256, nullable=true)
     */
    private $seoDescription;

    /**
     * CustomPage constructor.
     * @param $site
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * @return mixed
     */
    public function getNameMenu()
    {
        return $this->nameMenu;
    }

    /**
     * @param mixed $nameMenu
     */
    public function setNameMenu($nameMenu)
    {
        $this->nameMenu = $nameMenu;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param string $seoTitle
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;
    }

    /**
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * @param string $seoDescription
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;
    }

    /**
     * @return string
     */
    public function getSeoDescription()
    {
        return $this->seoDescription;
    }

    /**
     * @return mixed
     */
    public function getNameMenuUrl()
    {
        return $this->nameMenuUrl;
    }

    /**
     * @param mixed $nameMenuUrl
     */
    public function setNameMenuUrl($nameMenuUrl)
    {
        $this->nameMenuUrl = $nameMenuUrl;
    }
}
