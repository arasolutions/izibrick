<?php


namespace App\Firebrock\CommandHandler;

use App\Entity\TrackingQuote;
use App\Firebrock\Command\RemoveBlogCommand;
use App\Firebrock\Command\RemoveTrackingQuoteCommand;
use App\Repository\TrackingQuoteRepository;

/**
 * Class RemoveBlogCommandHandler
 * @package App\Firebrock\CommandHandler
 */
class RemoveTrackingQuoteCommandHandler
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
     * @param RemoveTrackingQuoteCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(RemoveTrackingQuoteCommand $command)
    {
        $id = $command->id;

        /** @var Blog $blog */
        $trackingQuote = $this->trackingQuoteRepository->get($id);

        if (!$trackingQuote) {
            $message = sprintf("Error removing trackingQuote : ID (%d) doesn't exist", $id);
            throw new \InvalidArgumentException($message);
        }

        $this->trackingQuoteRepository->remove($trackingQuote);
    }

}