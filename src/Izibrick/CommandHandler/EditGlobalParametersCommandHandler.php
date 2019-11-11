<?php


namespace App\Izibrick\CommandHandler;


use App\Entity\Site;
use App\Helper\ColorHelper;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Repository\SiteRepository;

class EditGlobalParametersCommandHandler
{
    /** @var SiteRepository */
    private $siteRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
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
        $site->setDescription($command->getDescription());
        $site->setDomain($command->getDomain());
        $site->setKeyWords($command->getKeys());
        $site->setFacebook($command->getFacebook());
        $site->setTwitter($command->getTwitter());
        $site->setInstagram($command->getInstagram());
        $site->setTemplate($command->getTemplate());
        $site->setColorTheme($command->getColorTheme());

        // détection de la couleur du texte en fonction du fond choisi
        $luminance = ColorHelper::getLuminance(ColorHelper::hexaToRgb($command->getColorTheme()));
        if ($luminance > .30) {
            $site->setTextColor("#222222");
        } else {
            $site->setTextColor("#FFFFFF");
        }

        if ($command->getLogo() != null) {
            $site->setLogo('');
            $site->setLogoFile($command->getLogo());
        }
        $site->setNameInLogo($command->getNameInLogo());
        $this->siteRepository->save($site);
    }

}