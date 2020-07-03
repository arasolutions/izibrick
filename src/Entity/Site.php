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
 * @ORM\Table(name="fir_site", indexes={
 *     @ORM\Index(name="site_internal_name_idx", columns="internal_name", options={"where": "(domain IS NOT NULL)"}),
 *     @ORM\Index(name="site_domain_host_idx", columns="domain_host", options={"where": "(domain_actif = 1)"})
 *     })
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
     * @ORM\Column(type="string", length=127)
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
     * @var ArrayCollection|CustomPage[]
     * @ORM\OneToMany(targetEntity="App\Entity\CustomPage", mappedBy="site", cascade={"persist"}, fetch="LAZY")
     */
    private $customPages;

    /**
     * @var ArrayCollection|Page[]
     * @ORM\OneToMany(targetEntity="App\Entity\Page", mappedBy="site", cascade={"persist"}, fetch="LAZY")
     */
    private $pages;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="sites", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @var ArrayCollection|PricingCategory[]
     * @ORM\OneToMany(targetEntity="App\Entity\PricingCategory", mappedBy="site", cascade={"persist"}, fetch="LAZY")
     */
    private $pricingCategories;

    /**
     * @var ArrayCollection|PricingProduct[]
     * @ORM\OneToMany(targetEntity="App\Entity\PricingProduct", mappedBy="site", cascade={"persist"}, fetch="LAZY")
     */
    private $pricingProducts;

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
     * @ORM\OneToOne(targetEntity="App\Entity\Pricing", mappedBy="site", cascade={"persist", "remove"})
     */
    private $pricing;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Invoice", mappedBy="site")
     */
    private $invoices;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $domain;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $domainHost;

    /**
     * @ORM\Column(type="string", length=1023, nullable=true)
     */
    private $keyWords;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $favicon;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="favicon_site", fileNameProperty="favicon")
     */
    private $faviconFile;

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
     * @var ArrayCollection|TrackingQuote[]
     * @ORM\OneToMany(targetEntity="App\Entity\TrackingQuote", mappedBy="site", cascade={"persist"}, fetch="LAZY")
     */
    private $trackingQuotes;

    /**
     * @var ArrayCollection|TrackingContact[]
     * @ORM\OneToMany(targetEntity="App\Entity\TrackingContact", mappedBy="site", cascade={"persist"}, fetch="LAZY")
     */
    private $trackingContacts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CodePromotion", inversedBy="sites")
     */
    private $codePromotion;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $textColor;

    /**
     * @ORM\Column(type="boolean")
     */
    private $nameInLogo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $domainActif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $internalName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $lightTheme;

    /**
     * @ORM\Column(type="string", length=65, nullable=true)
     */
    private $stripeSubscriptionId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commandDomain;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $commandOption;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Font")
     * @ORM\JoinColumn(nullable=false)
     */
    private $font;

    /**
     * @ORM\Column(type="smallint")
     */
    private $fontSize;

    /**
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    private $analyticsViewId;

    /**
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    private $analyticsSuiviId;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Page", inversedBy="defaultPage", cascade={"persist", "remove"})
     */
    private $defaultPage;

    /**
     * Site constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->status = SiteStatus::INITIALISE['name'];
        $this->users = new ArrayCollection();
        $this->nameInLogo = false;
        $this->domainActif = false;
        $this->lightTheme = true;
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

    /**
     * @return ArrayCollection|CustomPage[]
     */
    public function getCustomPages()
    {
        return $this->customPages;
    }

    /**
     * @return ArrayCollection|Page[]
     */
    public function getPages()
    {
        return $this->pages;
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
     * @return ArrayCollection|PricingCategory[]
     */
    public function getPricingCategories()
    {
        return $this->pricingCategories;
    }

    /**
     * @return ArrayCollection|PricingProduct[]
     */
    public function getPricingProducts()
    {
        return $this->pricingProducts;
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

    public function getPricing(): ?Pricing
    {
        return $this->pricing;
    }

    public function setPricing(?Pricing $pricing): self
    {
        $this->pricing = $pricing;

        // set (or unset) the owning side of the relation if necessary
        $newSite = $pricing === null ? null : $this;
        if ($newSite !== $pricing->getSite()) {
            $pricing->setSite($newSite);
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


    /**
     * @return Collection|Invoice[]
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices[] = $invoice;
            $invoice->setSite($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->contains($invoice)) {
            $this->invoices->removeElement($invoice);
            // set the owning side to null (unless already changed)
            if ($invoice->getSite() === $this) {
                $invoice->setSite(null);
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

    /**
     * @return mixed
     */
    public function getDomainHost()
    {
        return $this->domainHost;
    }

    /**
     * @param mixed $domainHost
     */
    public function setDomainHost($domainHost)
    {
        $this->domainHost = $domainHost;
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

    /**
     * @return ArrayCollection|TrackingContact[]
     */
    public function getTrackingContacts()
    {
        return $this->trackingContacts;
    }

    public function getCodePromotion(): ?CodePromotion
    {
        return $this->codePromotion;
    }

    public function setCodePromotion(?CodePromotion $codePromotion): self
    {
        $this->codePromotion = $codePromotion;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasOneSocialLink()
    {
        return $this->getFacebook() != null ||
            $this->getTwitter() != null ||
            $this->getInstagram() != null;

    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    public function setTextColor(string $textColor): self
    {
        $this->textColor = $textColor;

        return $this;
    }

    public function getNameInLogo(): ?bool
    {
        return $this->nameInLogo;
    }

    public function setNameInLogo(bool $nameInLogo): self
    {
        $this->nameInLogo = $nameInLogo;

        return $this;
    }

    public function getDomainActif(): ?bool
    {
        return $this->domainActif;
    }

    public function setDomainActif(bool $domainActif): self
    {
        $this->domainActif = $domainActif;

        return $this;
    }

    public function getInternalName(): ?string
    {
        return $this->internalName;
    }

    public function setInternalName(string $internalName): self
    {
        $this->internalName = $internalName;

        return $this;
    }

    public function getLightTheme(): ?bool
    {
        return $this->lightTheme;
    }

    public function setLightTheme(bool $lightTheme): self
    {
        $this->lightTheme = $lightTheme;

        return $this;
    }

    /**
     * @return File
     */
    public function getFaviconFile(): ?File
    {
        return $this->faviconFile;
    }

    /**
     * @param File $faviconFile
     */
    public function setFaviconFile(File $faviconFile): void
    {
        $this->faviconFile = $faviconFile;
    }

    public function getStripeSubscriptionId(): ?string
    {
        return $this->stripeSubscriptionId;
    }

    public function setStripeSubscriptionId(string $stripeSubscriptionId): self
    {
        $this->stripeSubscriptionId = $stripeSubscriptionId;

        return $this;
    }

    public function getCommandDomain(): ?string
    {
        return $this->commandDomain;
    }

    public function setCommandDomain(?string $commandDomain): self
    {
        $this->commandDomain = $commandDomain;

        return $this;
    }

    public function getCommandOption(): ?int
    {
        return $this->commandOption;
    }

    public function setCommandOption(int $commandOption): self
    {
        $this->commandOption = $commandOption;

        return $this;
    }

    public function getTwitterKey(): ?string
    {
        return str_replace("https://www.twitter.com", "", $this->twitter);
    }

    public function getFacebookKey(): ?string
    {
        return str_replace("https://www.facebook.com", "", $this->facebook);
    }

    public function getInstagramKey(): ?string
    {
        return str_replace("https://www.instagram.com", "", $this->instagram);
    }

    public function getFont(): ?Font
    {
        return $this->font;
    }

    public function setFont(?Font $font): self
    {
        $this->font = $font;

        return $this;
    }

    public function getFontSize(): ?int
    {
        return $this->fontSize;
    }

    public function setFontSize(int $fontSize): self
    {
        $this->fontSize = $fontSize;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnalyticsViewId()
    {
        return $this->analyticsViewId;
    }

    /**
     * @param mixed $analyticsViewId
     */
    public function setAnalyticsViewId($analyticsViewId)
    {
        $this->analyticsViewId = $analyticsViewId;
    }

    /**
     * @return mixed
     */
    public function getAnalyticsSuiviId()
    {
        return $this->analyticsSuiviId;
    }

    /**
     * @param mixed $analyticsSuiviId
     */
    public function setAnalyticsSuiviId($analyticsSuiviId)
    {
        $this->analyticsSuiviId = $analyticsSuiviId;
    }

    /**
     * @return mixed
     */
    public function getDefaultPage()
    {
        return $this->defaultPage;
    }

    /**
     * @param mixed $defaultPage
     */
    public function setDefaultPage($defaultPage)
    {
        $this->defaultPage = $defaultPage;
    }
}
