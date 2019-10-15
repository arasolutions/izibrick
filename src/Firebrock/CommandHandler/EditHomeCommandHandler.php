<?php


namespace App\Firebrock\CommandHandler;


use App\Entity\Site;
use App\Firebrock\Command\GlobalParametersCommand;
use App\Firebrock\Command\HomeCommand;
use App\Repository\SiteRepository;

/**
 * Class EditHomeCommandHandler
 * @package App\Firebrock\CommandHandler
 */
class EditHomeCommandHandler
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
     * @param HomeCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(HomeCommand $command, Site $site)
    {
        $site->getHome()->setContent($command->getContent());
        if ($command->getMainPicture() != null) {
            $site->getHome()->setMainPictureFile($command->getMainPicture());
        }
        $this->siteRepository->save($site);
    }

}