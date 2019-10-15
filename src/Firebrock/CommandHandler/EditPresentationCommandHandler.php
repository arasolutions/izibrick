<?php


namespace App\Firebrock\CommandHandler;


use App\Entity\Site;
use App\Firebrock\Command\GlobalParametersCommand;
use App\Firebrock\Command\HomeCommand;
use App\Firebrock\Command\PresentationCommand;
use App\Repository\SiteRepository;

/**
 * Class EditPresentationCommandHandler
 * @package App\Firebrock\CommandHandler
 */
class EditPresentationCommandHandler
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
     * @param PresentationCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(PresentationCommand $command, Site $site)
    {
        $site->getPresentation()->setContent($command->getContent());
        $this->siteRepository->save($site);
    }

}