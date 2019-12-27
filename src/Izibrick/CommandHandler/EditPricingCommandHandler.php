<?php


namespace App\Izibrick\CommandHandler;


use App\Entity\Site;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Izibrick\Command\HomeCommand;
use App\Izibrick\Command\PricingCommand;
use App\Repository\SiteRepository;

/**
 * Class EditPricingCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class EditPricingCommandHandler
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
     * @param PricingCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(PricingCommand $command, Site $site)
    {
        $site->getPricing()->setContent($command->getContent());
        $this->siteRepository->save($site);
    }

}