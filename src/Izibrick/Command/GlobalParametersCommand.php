<?php


namespace App\Izibrick\Command;

use App\Entity\Font;
use App\Entity\Product;
use App\Entity\Site;

class GlobalParametersCommand
{
    private $name;
    private $description;
    private $domain;
    private $logo;
    private $nameInLogo;
    private $keys;
    private $favicon;
    private $facebook;
    private $twitter;
    private $instagram;
    private $template;
    private $colorTheme;
    private $lightTheme;
    private $displayPricing;
    private $displayQuote;
    private $font;
    private $fontSize;

    /**
     * GlobalParametersCommand constructor.
     * @param Site $site
     */
    public function __construct(Site $site)
    {
        $this->name = $site->getName();
        $this->description = $site->getDescription();
        $this->domain = $site->getDomain();
        $this->instagram = $site->getInstagram();
        $this->facebook = $site->getFacebook();
        $this->keys = $site->getKeyWords();
        $this->twitter = $site->getTwitter();
        $this->logo = $site->getLogo();
        $this->nameInLogo = $site->getNameInLogo();
        $this->template = $site->getTemplate();
        $this->colorTheme = $site->getColorTheme();
        $this->lightTheme = $site->getLightTheme();
        $this->displayPricing = $site->getPricing()->getDisplay();
        $this->displayQuote = $site->getQuote()->getDisplay();
        $this->font = $site->getFont();
        $this->fontSize = $site->getFontSize();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }


    /**
     * @return null|string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param null|string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo): void
    {
        $this->logo = $logo;
    }

    /**
     * @return mixed
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * @param mixed $keys
     */
    public function setKeys($keys): void
    {
        $this->keys = $keys;
    }

    /**
     * @return mixed
     */
    public function getFavicon()
    {
        return $this->favicon;
    }

    /**
     * @param mixed $favicon
     */
    public function setFavicon($favicon): void
    {
        $this->favicon = $favicon;
    }

    /**
     * @return mixed
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param mixed $facebook
     */
    public function setFacebook($facebook): void
    {
        $this->facebook = $facebook;
    }

    /**
     * @return mixed
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param mixed $twitter
     */
    public function setTwitter($twitter): void
    {
        $this->twitter = $twitter;
    }

    /**
     * @return mixed
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * @param mixed $instagram
     */
    public function setInstagram($instagram): void
    {
        $this->instagram = $instagram;
    }

    /**
     * @return \App\Entity\Template|null
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param \App\Entity\Template|null $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return null|string
     */
    public function getColorTheme()
    {
        return $this->colorTheme;
    }

    /**
     * @param null|string $colorTheme
     */
    public function setColorTheme($colorTheme)
    {
        $this->colorTheme = $colorTheme;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getNameInLogo()
    {
        return $this->nameInLogo;
    }

    /**
     * @param mixed $nameInLogo
     */
    public function setNameInLogo($nameInLogo): void
    {
        $this->nameInLogo = $nameInLogo;
    }

    /**
     * @return bool|null
     */
    public function getLightTheme(): ?bool
    {
        return $this->lightTheme;
    }

    /**
     * @param bool|null $lightTheme
     */
    public function setLightTheme(?bool $lightTheme): void
    {
        $this->lightTheme = $lightTheme;
    }

    /**
     * @return bool|null
     */
    public function getDisplayPricing(): ?bool
    {
        return $this->displayPricing;
    }

    /**
     * @param bool|null $displayPricing
     */
    public function setDisplayPricing(?bool $displayPricing): void
    {
        $this->displayPricing = $displayPricing;
    }

    /**
     * @return bool|null
     */
    public function getDisplayQuote(): ?bool
    {
        return $this->displayQuote;
    }

    /**
     * @param bool|null $displayQuote
     */
    public function setDisplayQuote(?bool $displayQuote): void
    {
        $this->displayQuote = $displayQuote;
    }

    /**
     * @return Font|null
     */
    public function getFont(): Font
    {
        return $this->font;
    }

    /**
     * @param Font|null $font
     */
    public function setFont(?Font $font): void
    {
        $this->font = $font;
    }

    /**
     * @return int|null
     */
    public function getFontSize(): int
    {
        return $this->fontSize;
    }

    /**
     * @param int|null $fontSize
     */
    public function setFontSize(int $fontSize): void
    {
        $this->fontSize = $fontSize;
    }


}