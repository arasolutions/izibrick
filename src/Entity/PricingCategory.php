<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PricingCategoryRepository")
 * @ORM\Table(name="fir_pricing_category")
 */
class PricingCategory
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="pricingCategories", fetch="EAGER")
     */
    private $site;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @var ArrayCollection|PricingProduct[]
     * @ORM\OneToMany(targetEntity="App\Entity\PricingProduct", mappedBy="category", cascade={"persist"}, fetch="LAZY")
     */
    private $products;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", length=1023, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(name="display_order", type="integer", length=16, nullable=true)
     */
    private $displayOrder;

    public function __construct(Site $site)
    {
        $this->site = $site;
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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return ArrayCollection|PricingProduct[]
     */
    public function getProducts()
    {
        return $this->products;
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
            $site->setPricingCategory($this);
        }

        return $this;
    }

    public function removeSite(Site $site): self
    {
        if ($this->sites->contains($site)) {
            $this->sites->removeElement($site);
            // set the owning side to null (unless already changed)
            if ($site->getPricingCategory() === $this) {
                $site->setPricingCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection|CodePromotion[]
     */
    public function getCodesPromotion()
    {
        return $this->codesPromotion;
    }

    /**
     * @return bool
     */
    public function hasCodesPromotion()
    {
        if (empty($this->codesPromotion) || is_null($this->codesPromotion)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     * @param mixed $displayOrder
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;
    }
}
