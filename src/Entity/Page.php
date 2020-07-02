<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 * @ORM\Table(name="fir_page")
 */
class Page
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="pages", fetch="EAGER")
     */
    private $site;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PageTypeHome", mappedBy="page", cascade={"persist", "remove"})
     */
    private $pagesTypeHome;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PageTypePresentation", mappedBy="page", cascade={"persist", "remove"})
     */
    private $pagesTypePresentation;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PageTypeContact", mappedBy="page", cascade={"persist", "remove"})
     */
    private $pagesTypeContact;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PageTypeBlog", mappedBy="page", cascade={"persist", "remove"})
     */
    private $pagesTypeBlog;

    /**
     * @var ArrayCollection|Post[]
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="page", cascade={"persist"}, fetch="LAZY")
     */
    private $posts;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default":1})
     */
    private $place;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default":1})
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $menuOrder;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $menuHeaderOrder;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $menuFooterOrder;

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
     * Page constructor.
     * @param $site
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
        $this->createdAt = new \DateTime();
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
    public function getPagesTypeHome()
    {
        return $this->pagesTypeHome;
    }

    /**
     * @param mixed $pagesTypeHome
     */
    public function setPagesTypeHome($pagesTypeHome)
    {
        $this->pagesTypeHome = $pagesTypeHome;
    }

    /**
     * @return mixed
     */
    public function getPagesTypePresentation()
    {
        return $this->pagesTypePresentation;
    }

    /**
     * @param mixed $pagesTypePresentation
     */
    public function setPagesTypePresentation($pagesTypePresentation)
    {
        $this->pagesTypePresentation = $pagesTypePresentation;
    }

    /**
     * @return mixed
     */
    public function getPagesTypeContact()
    {
        return $this->pagesTypeContact;
    }

    /**
     * @param mixed $pagesTypeContact
     */
    public function setPagesTypeContact($pagesTypeContact)
    {
        $this->pagesTypeContact = $pagesTypeContact;
    }

    /**
     * @return mixed
     */
    public function getPagesTypeBlog()
    {
        return $this->pagesTypeBlog;
    }

    /**
     * @param mixed $pagesTypeBlog
     */
    public function setPagesTypeBlog($pagesTypeBlog)
    {
        $this->pagesTypeBlog = $pagesTypeBlog;
    }

    /**
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param mixed $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
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

    /**
     * @return mixed
     */
    public function getMenuOrder()
    {
        return $this->menuOrder;
    }

    /**
     * @param mixed $menuOrder
     */
    public function setMenuOrder($menuOrder)
    {
        $this->menuOrder = $menuOrder;
    }

    /**
     * @return mixed
     */
    public function getMenuHeaderOrder()
    {
        return $this->menuHeaderOrder;
    }

    /**
     * @param mixed $menuHeaderOrder
     */
    public function setMenuHeaderOrder($menuHeaderOrder)
    {
        $this->menuHeaderOrder = $menuHeaderOrder;
    }

    /**
     * @return mixed
     */
    public function getMenuFooterOrder()
    {
        return $this->menuFooterOrder;
    }

    /**
     * @param mixed $menuFooterOrder
     */
    public function setMenuFooterOrder($menuFooterOrder)
    {
        $this->menuFooterOrder = $menuFooterOrder;
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
