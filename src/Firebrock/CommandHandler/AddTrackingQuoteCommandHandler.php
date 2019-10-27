<?php


namespace App\Firebrock\CommandHandler;

use App\Entity\Site;
use App\Entity\TrackingQuote;
use App\Firebrock\Command\AddTrackingQuoteCommand;
use App\Repository\TrackingQuoteRepository;

/**
 * Class AddBlogCommandHandler
 * @package App\Firebrock\CommandHandler
 */
class AddTrackingQuoteCommandHandler
{
    /** @var TrackingQuoteRepository $trackingQuoteRepository */
    private $trackingQuoteRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param TrackingQuoteRepository $trackingQuoteRepository
     */
    public function __construct(TrackingQuoteRepository $trackingQuoteRepository)
    {
        $this->trackingQuoteRepository = $trackingQuoteRepository;
    }

    /**
     * @param AddTrackingQuoteCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(AddTrackingQuoteCommand $command, Site $site)
    {
        $trackingQuote = new TrackingQuote();
        $trackingQuote->setName($command->name);
        $trackingQuote->setEmail($command->email);
        $trackingQuote->setContent($command->content);
        $trackingQuote->setSite($site);
        $this->trackingQuoteRepository->save($trackingQuote);
    }

}