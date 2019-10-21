<?php


namespace App\Firebrock\CommandHandler;


use App\Entity\Site;
use App\Entity\Contact;
use App\Firebrock\Command\GlobalParametersCommand;
use App\Firebrock\Command\HomeCommand;
use App\Firebrock\Command\SeoCommand;
use App\Repository\SiteRepository;
use App\Repository\ContactRepository;

/**
 * Class EditSeoCommandHandler
 * @package App\Firebrock\CommandHandler
 */
class EditSeoCommandHandler
{
    /** @var ContactRepository $contactRepository */
    private $contactRepository;

    /** @var SiteRepository */
    private $siteRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(ContactRepository $contactRepository, SiteRepository $siteRepository)
    {
        $this->contactRepository = $contactRepository;
        $this->siteRepository = $siteRepository;
    }

    /**
     * @param SeoCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(SeoCommand $command, Site $site)
    {
        $id = $site->getId();
        $contact = $this->contactRepository->getBySiteId($id);
        if ($contact) {
            $contact->setSeoTitle($command->seoTitleContact);
            $contact->setSeoDescription($command->seoDescriptionContact);
            $this->contactRepository->save($contact);
        }
    }

}