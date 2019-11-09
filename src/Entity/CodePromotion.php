<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CodePromotionRepository")
 * @ORM\Table(name="fir_product_code_promotion")
 */
class CodePromotion
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="codesPromotion", fetch="EAGER")
     */
    private $product;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $priceDecrease;

    /**
     * @ORM\Column(type="date")
     */
    private $dateBegin;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateEnd;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Site", mappedBy="codePromotion")
     */
    private $sites;

    /**
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    private $stripePlanTarifaireId;

    /**
     * Presentation constructor.
     * @param $site
     * @throws \Exception
     */
    public function __construct($site)
    {
        $this->site = $site;
        $this->dateBegin = new \DateTime();
        $this->sites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPriceDecrease()
    {
        return $this->priceDecrease;
    }

    /**
     * @param mixed $priceDecrease
     */
    public function setPriceDecrease($priceDecrease)
    {
        $this->priceDecrease = $priceDecrease;
    }

    public function getDateBegin(): ?\DateTimeInterface
    {
        return $this->dateBegin;
    }

    public function setDateBegin(\DateTimeInterface $dateBegin): self
    {
        $this->dateBegin = $dateBegin;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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
            $site->setCodePromotion($this);
        }

        return $this;
    }

    public function removeSite(Site $site): self
    {
        if ($this->sites->contains($site)) {
            $this->sites->removeElement($site);
            // set the owning side to null (unless already changed)
            if ($site->getCodePromotion() === $this) {
                $site->setCodePromotion(null);
            }
        }

        return $this;
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
