<?php

namespace App\Entity;

use App\Enum\SiteStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="fir_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSite", mappedBy="user")
     */
    private $sites;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Support", mappedBy="user")
     */
    private $support;

    /**
     * @ORM\Column(type="string", length=63, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=63, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    private $stripeCustomerId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $account;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address2;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=127, nullable=true)
     */
    private $societyName;

    /**
     * @ORM\Column(type="boolean", options={"default":true})
     */
    private $active;

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        parent::setEmail($email);
        $this->setUsername($email);
    }

    public function __construct()
    {
        parent::__construct();
        $this->sites = new ArrayCollection();
        $this->support = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|UserSite[]
     */
    public function getSites(): Collection
    {
        $result = new ArrayCollection();
        /** @var UserSite $site */
        foreach ($this->sites as $site){
            if($site->getSite()->getStatus() == SiteStatus::INITIALISE['name'] || $site->getSite()->getStatus() == SiteStatus::ACTIF['name']) {
                $result->add($site);
            }
        }
        return $result;
    }

    public function addSite(UserSite $site): self
    {
        if (!$this->sites->contains($site)) {
            $this->sites[] = $site;
            $site->setUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|UserSite[]
     */
    public function getLastSite()
    {
        return $this->sites[sizeof($this->sites) - 1];
    }

    public function removeSite(UserSite $site): self
    {
        if ($this->sites->contains($site)) {
            $this->sites->removeElement($site);
            // set the owning side to null (unless already changed)
            if ($site->getUser() === $this) {
                $site->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Support[]
     */
    public function getSupport(): Collection
    {
        return $this->support;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        $fullname = '';
        if ($this->firstname == '' && $this->lastname == '') {
            $fullname = $this->username;
        } else {
            $this->firstname . ' ' . $this->lastname;
        }
        return $fullname;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStripeCustomerId()
    {
        return $this->stripeCustomerId;
    }

    /**
     * @param mixed $stripeCustomerId
     */
    public function setStripeCustomerId($stripeCustomerId)
    {
        $this->stripeCustomerId = $stripeCustomerId;
    }

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function setAccount(?string $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(?string $address1): self
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): self
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getSocietyName(): ?string
    {
        return $this->societyName;
    }

    public function setSocietyName(?string $societyName): self
    {
        $this->societyName = $societyName;

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
}
