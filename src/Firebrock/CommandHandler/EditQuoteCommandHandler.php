<?php


namespace App\Firebrock\CommandHandler;


use App\Entity\Site;
use App\Firebrock\Command\QuoteCommand;
use App\Firebrock\Command\GlobalParametersCommand;
use App\Firebrock\Command\HomeCommand;
use App\Firebrock\Command\PresentationCommand;
use App\Repository\SiteRepository;

/**
 * Class EditQuoteCommandHandler
 * @package App\Firebrock\CommandHandler
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