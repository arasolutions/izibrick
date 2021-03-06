<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TemplateRepository")
 * @ORM\Table(name="fir_template")
 */
class Template
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $urlExample;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageExample;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Site", mappedBy="template")
     */
    private $sites;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Site", mappedBy="template")
     */
    private $pageTypeBlogs;

    /**
     * @ORM\Column(name="public", type="boolean", options={"default":true})
     */
    private $public=true;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Font")
     * @ORM\JoinColumn(nullable=false)
     */
    private $defaultFont;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getUrlExample(): ?string
    {
        return $this->urlExample;
    }

    public function setUrlExample(string $urlExample): self
    {
        $this->urlExample = $urlExample;

        return $this;
    }

    public function getImageExample(): ?string
    {
        return $this->imageExample;
    }

    public function setImageExample(string $imageExample): self
    {
        $this->imageExample = $imageExample;

        return $this;
    }

    /**
     * @return Collection|Site[]
     */
    public function getSites(): Collection
    {
        return $this->sites;
    }

    public function addSite(Site $site): self
    {
        if (!$this->sites->contains($site)) {
            $this->sites[] = $site;
            $site->setTemplate($this);
        }

        return $this;
    }

    public function removeSite(Site $site): self
    {
        if ($this->sites->contains($site)) {
            $this->sites->removeElement($site);
            // set the owning side to null (unless already changed)
            if ($site->getTemplate() === $this) {
                $site->setTemplate(null);
            }
        }

        return $this;
    }

    /**
     * @param bool $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->public;
    }

    public function getDefaultFont(): ?Font
    {
        return $this->defaultFont;
    }

    public function setDefaultFont(?Font $defaultFont): self
    {
        $this->defaultFont = $defaultFont;

        return $this;
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
     * @return Collection|PageTypeBlog[]
     */
    public function getPageTypeBlogs(): Collection
    {
        return $this->pageTypeBlogs;
    }
}
