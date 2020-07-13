<?php

namespace App\Izibrick\CommandHandler;

use App\Entity\Site;
use App\Entity\Page;
use App\Helper\StringHelper;
use App\Izibrick\Command\AddPageCommand;
use App\Izibrick\Command\PageTypeContactCommand;
use App\Repository\PageTypeContactRepository;
use App\Repository\SiteRepository;
use App\Repository\PageRepository;

/**
 * Class AddSupportCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class EditPageTypeContactCommandHandler
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var PageRepository */
    private $pageRepository;

    /** @var PageTypeContactRepository */
    private $pageTypeContactRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     * @param PageRepository $pageRepository
     * @param PageTypeContactRepository $pageTypeContactRepository
     */
    public function __construct(SiteRepository $siteRepository, PageRepository $pageRepository, PageTypeContactRepository $pageTypeContactRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->pageRepository = $pageRepository;
        $this->pageTypeContactRepository = $pageTypeContactRepository;
    }

    /**
     * @param AddPageCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(PageTypeContactCommand $command, Site $site)
    {
        $page = $this->pageRepository->get($command->id);
        if (!$page) {
            throw new \Exception(sprintf('Error - Page not found (id: %d)', $command->id));
        }
        $pageTypeContact = $this->pageTypeContactRepository->getByPageId($command->id);
        if (!$pageTypeContact) {
            throw new \Exception(sprintf('Error - PageTypeContact not found (id: %d)', $command->id));
        }
        $page->setType($command->type->getId());
        $page->setNameMenu($command->name);
        $page->setNameMenuUrl(StringHelper::cleanUrl($command->name));
        $page->setDisplayMenuHeader($command->displayMenuHeader);
        $page->setDisplayMenuFooter($command->displayMenuFooter);
        $page->setSeoTitle($command->seoTitle);
        $page->setSeoDescription($command->seoDescription);
        $this->pageRepository->save($page);
        $pageTypeContact->setContent($command->content);
        $pageTypeContact->setPresentation($command->presentation);
        $pageTypeContact->setEmail($command->email);
        $pageTypeContact->setPhone($command->phone);
        $pageTypeContact->setName($command->nameAddress);
        $pageTypeContact->setPostCode($command->postCode);
        $pageTypeContact->setCity($command->city);
        $pageTypeContact->setCountry($command->country);
        $pageTypeContact->setOpeningTime($command->openingTime);
        $this->pageTypeContactRepository->save($pageTypeContact);
    }

}