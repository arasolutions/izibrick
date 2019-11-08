<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="fir_product")
 */
class Product
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
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $codePromotion;

    /**
     * @var ArrayCollection|CodePromotion[]
     * @ORM\OneToMany(targetEntity="App\Entity\CodePromotion", mappedBy="product", cascade={"persist"}, fetch="LAZY")
     */
    private $codesPromotion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Site", mappedBy="product")
     */
    private $sites;

    /**
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    private $stripePlanTarifaireId;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
        $this->codesPromotion = new ArrayCollection();
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

    public function getCodePromotion(): ?string
    {
        return $this->codePromotion;
    }

    public function setCodePromotion(?string $codePromotion): self
    {
        $this->codePromotion = $codePromotion;

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
            $site->setProduct($this);
        }

        return $this;
    }

    public function removeSite(Site $site): self
    {
        if ($this->sites->contains($site)) {
            $this->sites->removeElement($site);
            // set the owning side to null (unless already changed)
            if ($site->getProduct() === $this) {
                $site->setProduct(null);
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
     * @return mixed
     */
    public function getStripePlanTarifaireId()
    {
        return $this->stripePlanTarifaireId;
    }

    /**
     * @param mixed $stripePlanTarifaireId
     */
    public function setStripePlanTarifaireId($stripePlanTarifaireId)
    {
        $this->stripePlanTarifaireId = $stripePlanTarifaireId;
    }
}
