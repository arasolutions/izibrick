<?php


namespace App\Izibrick\CommandHandler;

use App\Entity\TrackingContact;
use App\Izibrick\Command\RemoveTrackingContactCommand;
use App\Repository\TrackingContactRepository;

/**
 * Class RemoveBlogCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class RemoveTrackingContactCommandHandler
{
    /** @var TrackingContactRepository $trackingContactRepository */
    private $trackingContactRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param TrackingContactRepository $trackingContactRepository
     */
    public function __construct(TrackingContactRepository $trackingContactRepository)
    {
        $this->trackingContactRepository = $trackingContactRepository;
    }

    /**
     * @param RemoveTrackingContactCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(RemoveTrackingContactCommand $command)
    {
        $id = $command->id;

        /** @var Blog $blog */
        $trackingContact = $this->trackingContactRepository->get($id);

        if (!$trackingContact) {
            $message = sprintf("Error removing trackingContact : ID (%d) doesn't exist", $id);
            throw new \InvalidArgumentException($message);
        }

        $this->trackingContactRepository->remove($trackingContact);
    }

}