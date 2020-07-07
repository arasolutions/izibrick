<?php


namespace App\Izibrick\CommandHandler;


use App\Entity\Pricing;
use App\Entity\Site;
use App\Enum\Constants;
use App\Helper\ColorHelper;
use App\Helper\SiteHelper;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Repository\FontRepository;
use App\Repository\PricingRepository;
use App\Repository\QuoteRepository;
use App\Repository\SiteRepository;

class EditGlobalParametersCommandHandler
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var FontRepository */
    private $fontRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     * @param PricingRepository $pricingRepository
     * @param QuoteRepository $quoteRepository
     * @param FontRepository $fontRepository
     */
    public function __construct(SiteRepository $siteRepository, FontRepository $fontRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->fontRepository = $fontRepository;
    }


    /**
     * @param GlobalParametersCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(GlobalParametersCommand $command, Site $site)
    {
        $site->setName($command->getName());
        $site->setDefaultPage($command->getDefaultPage());

        // Gestion du nom interne
        if ($site->getInternalName() == '') {
            $internalName = SiteHelper::generateInternalName($site);
            $site->setInternalName($site->getId() . '-' . $internalName);
        }

        $site->setFontSize($command->getFontSize());
        $site->setFont($this->fontRepository->findOneBy(array('id' => $command->getFont())));

        $site = $this->siteRepository->save($site);

        $site->setDescription($command->getDescription());
        $site->setDisplayBoxed($command->getDisplayBoxed());
        $site->setDomain($command->getDomain());
        if ($command->getDomain() != '') {
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

        //var_dump($luminance);die();
        $textColor = "#FFFFFF";
        if ($luminance > Constants::LUMINANCE_THRESHOLD) {
            $textColor = "#222222";
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