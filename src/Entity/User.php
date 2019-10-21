<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
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
        return $this->sites;
    }

    public function addSite(UserSite $site): self
    {
        if (!$this->sites->contains($site)) {
            $this->sites[] = $site;
            $site->setUser($this);
        }

        return $this;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
