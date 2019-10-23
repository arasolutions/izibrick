<?php

namespace App\Entity;

use App\Enum\SiteStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SiteRepository")
 * @Vich\Uploadable
 */
class Site
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="sites")
     * @ORM\JoinColumn(nullable=true)
     */
    private $customer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=9)
     */
    private $colorTheme;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Template", inversedBy="sites", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $template;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="sites", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="logo_site", fileNameProperty="logo")
     */
    private $logoFile;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Home", mappedBy="site", cascade={"persist", "remove"})
     */
    private $home;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Presentation", mappedBy="site", cascade={"persist", "remove"})
     */
    private $presentation;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Blog", mappedBy="site", cascade={"persist", "remove"})
     */
    private $blog;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Quote", mappedBy="site", cascade={"persist", "remove"})
     */
    private $quote;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Contact", mappedBy="site", cascade={"persist", "remove"})
     */
    private $contact;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSite", mappedBy="site")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $domain;

    /**
     * @ORM\Column(type="string", length=1023, nullable=true)
     */
    private $keyWords;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $favicon;

    /**
     * @ORM\Column(type="string", length=1023, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=1023, nullable=true)
     */
    private $instagram;

    /**
     * @ORM\Column(type="string", length=1023, nullable=true)
     */
    private $twitter;

    /**
     * @var ArrayCollection|Posts[]
     * @ORM\OneToMany(targetEntity="App\Entity\TrackingQuote", mappedBy="site", cascade={"persist"}, fetch="LAZY")
     */
    private $trackingQuotes;

    /**
     * Site constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->status = SiteStatus::A_CREER['name'];
        $this->users = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo(string $logo): void
    {
        $this->logo = $logo;
    }

    /**
     * @return File
     */
    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    /**
     * @param File $logoFile
     */
    public function setLogoFile(File $logoFile): void
    {
        $this->logoFile = $logoFile;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getColorTheme(): ?string
    {
        return $this->colorTheme;
    }

    public function setColorTheme(string $colorTheme): self
    {
        $this->colorTheme = $colorTheme;

        return $this;
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): self
    {
        $this->template = $template;

        return $this;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getHome(): ?Home
    {
        return $this->home;
    }

    public function setHome(?Home $home): self
    {
        $this->home = $home;

        // set (or unset) the owning side of the relation if necessary
        $newSite = $home === null ? null : $this;
        if ($newSite !== $home->getSite()) {
            $home->setSite($newSite);
        }

        return $this;
    }

    public function getPresentation(): ?Presentation
    {
        return $this->presentation;
    }

    public function setPresentation(?Presentation $presentation): self
    {
        $this->presentation = $presentation;

        // set (or unset) the owning side of the relation if necessary
        $newSite = $presentation === null ? null : $this;
        if ($newSite !== $presentation->getSite()) {
            $presentation->setSite($newSite);
        }

        return $this;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): self
    {
        $this->blog = $blog;

        // set (or unset) the owning side of the relation if necessary
        $newSite = $blog === null ? null : $this;
        if ($newSite !== $blog->getSite()) {
            $blog->setSite($newSite);
        }

        return $this;
    }

    public function getQuote(): ?Quote
    {
        return $this->quote;
    }

    public function setQuote(?Quote $quote): self
    {
        $this->quote = $quote;

        // set (or unset) the owning side of the relation if necessary
        $newSite = $quote === null ? null : $this;
        if ($newSite !== $quote->getSite()) {
            $quote->setSite($newSite);
        }

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;

        // set (or unset) the owning side of the relation if necessary
        $newSite = $contact === null ? null : $this;
        if ($newSite !== $contact->getSite()) {
            $contact->setSite($newSite);
        }

        return $this;
    }

    /**
     * @return Collection|UserSite[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(UserSite $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setSite($this);
        }

        return $this;
    }

    public function removeUser(UserSite $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getSite() === $this) {
                $user->setSite(null);
            }
        }

        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(?string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getKeyWords(): ?string
    {
        return $this->keyWords;
    }

    public function setKeyWords(?string $keyWords): self
    {
        $this->keyWords = $keyWords;

        return $this;
    }

    public function getFavicon(): ?string
    {
        return $this->favicon;
    }

    public function setFavicon(?string $favicon): self
    {
        $this->favicon = $favicon;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }


    /**
     * @return ArrayCollection|TrackingQuote[]
     */
    public function getTrackingQuotes()
    {
        return $this->trackingQuotes;
    }
}
