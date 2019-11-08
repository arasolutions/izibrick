<?php


namespace App\Izibrick\CommandHandler;

use App\Entity\Site;
use App\Entity\TrackingContact;
use App\Izibrick\Command\AddTrackingContactCommand;
use App\Repository\TrackingContactRepository;

/**
 * Class AddBlogCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class AddTrackingContactCommandHandler
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
     * @param AddTrackingContactCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(AddTrackingContactCommand $command, Site $site)
    {
        $trackingContact = new TrackingContact();
        $trackingContact->setName($command->name);
        $trackingContact->setEmail($command->email);
        $trackingContact->setContent($command->content);
        $trackingContact->setSite($site);
        $this->trackingContactRepository->save($trackingContact);
    }

}