<?php


namespace App\Izibrick\CommandHandler;


use App\Entity\Site;
use App\Izibrick\Command\QuoteCommand;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Izibrick\Command\HomeCommand;
use App\Izibrick\Command\PresentationCommand;
use App\Repository\SiteRepository;

/**
 * Class EditQuoteCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class EditQuoteCommandHandler
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
     * @param QuoteCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(QuoteCommand $command, Site $site)
    {
        $site->getQuote()->setPresentation($command->presentation);
        $site->getQuote()->setEmail($command->email);
        $this->siteRepository->save($site);
    }

}