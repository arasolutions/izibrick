<?php


namespace App\Izibrick\CommandHandler;


use App\Entity\Site;
use App\Izibrick\Command\ContactCommand;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Izibrick\Command\HomeCommand;
use App\Izibrick\Command\PresentationCommand;
use App\Repository\SiteRepository;

/**
 * Class EditContactCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class EditContactCommandHandler
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
     * @param ContactCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(ContactCommand $command, Site $site)
    {
        $site->getContact()->setPresentation($command->presentation);
        $site->getContact()->setEmail($command->email);
        $site->getContact()->setPhone($command->phone);
        $site->getContact()->setName($command->name);
        $site->getContact()->setPostCode($command->postCode);
        $site->getContact()->setCity($command->city);
        $site->getContact()->setCountry($command->country);
        $site->getContact()->setOpeningTime($command->openingTime);
        $this->siteRepository->save($site);
    }

}