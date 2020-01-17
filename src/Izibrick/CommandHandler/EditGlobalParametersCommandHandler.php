<?php


namespace App\Izibrick\CommandHandler;


use App\Entity\Pricing;
use App\Entity\Site;
use App\Enum\Constants;
use App\Helper\ColorHelper;
use App\Helper\SiteHelper;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Repository\PricingRepository;
use App\Repository\QuoteRepository;
use App\Repository\SiteRepository;

class EditGlobalParametersCommandHandler
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var PricingRepository */
    private $pricingRepository;

    /** @var QuoteRepository */
    private $quoteRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(SiteRepository $siteRepository, PricingRepository $pricingRepository, QuoteRepository $quoteRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->pricingRepository = $pricingRepository;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param GlobalParametersCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(GlobalParametersCommand $command, Site $site)
    {
        $pricing = $this->pricingRepository->getBySiteId($site->getId());
        $quote = $this->quoteRepository->getBySiteId($site->getId());

        $pricing->setDisplay($command->getDisplayPricing());
        $this->pricingRepository->save($pricing);

        $quote->setDisplay($command->getDisplayQuote());
        $this->quoteRepository->save($quote);

        $site->setName($command->getName());

        // Gestion du nom interne
        if($site->getInternalName() == '') {
            $internalName = SiteHelper::generateInternalName($site);
            $site->setInternalName($site->getId() . '-' . $internalName);
        }
        $site = $this->siteRepository->save($site);

        $site->setDescription($command->getDescription());
        $site->setDomain($command->getDomain());
        if($command->getDomain() != ''){
            // Enlever le https
            $site->setDomainHost(substr($command->getDomain(), 8));
        }
        $site->setKeyWords($command->getKeys());
        $site->setFacebook($command->getFacebook());
        $site->setTwitter($command->getTwitter());
        $site->setInstagram($command->getInstagram());
        $site->setTemplate($command->getTemplate());
        $site->setColorTheme($command->getColorTheme());
        $site->setLightTheme($command->getLightTheme());

        // détection de la couleur du texte en fonction du fond choisi
        $luminance = ColorHelper::getLuminance(ColorHelper::hexaToRgb($command->getColorTheme()));
        $textColor = "#FFFFFF";
        if ($luminance > Constants::LUMINANCE_THRESHOLD) {
            $textColor="#222222";
        }

        $site->setTextColor($textColor);

        if ($command->getLogo() != null) {
            $site->setLogo('');
            $site->setLogoFile($command->getLogo());
        }
        $site->setNameInLogo($command->getNameInLogo());

        if ($command->getFavicon() != null) {
            $site->setFavicon('');
            $site->setFaviconFile($command->getFavicon());
        }
        $this->siteRepository->save($site);
    }

}