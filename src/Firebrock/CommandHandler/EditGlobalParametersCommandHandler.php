<?php


namespace App\Firebrock\CommandHandler;


use App\Entity\Site;
use App\Firebrock\Command\GlobalParametersCommand;
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
        $site->setDomain($command->getDomain());
        $site->setKeyWords($command->getKeys());
        $site->setFacebook($command->getFacebook());
        $site->setTwitter($command->getTwitter());
        $site->setInstagram($command->getInstagram());
        if ($command->getLogo() != null) {
            $site->setLogo('');
            $site->setLogoFile($command->getLogo());
        }
        $this->siteRepository->save($site);
    }

}