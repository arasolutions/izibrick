<?php

namespace App\Izibrick\CommandHandler;

use App\Entity\Site;
use App\Entity\Page;
use App\Izibrick\Command\AddPageCommand;
use App\Izibrick\Command\PageTypePresentationCommand;
use App\Repository\PageTypePresentationRepository;
use App\Repository\SiteRepository;
use App\Repository\PageRepository;

/**
 * Class AddSupportCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class EditPageTypePresentationCommandHandler
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var PageRepository */
    private $pageRepository;

    /** @var PageTypePresentationRepository */
    private $pageTypePresentationRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     * @param PageRepository $pageRepository
     * @param PageTypePresentationRepository $pageTypePresentationRepository
     */
    public function __construct(SiteRepository $siteRepository, PageRepository $pageRepository, PageTypePresentationRepository $pageTypePresentationRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->pageRepository = $pageRepository;
        $this->pageTypePresentationRepository = $pageTypePresentationRepository;
    }

    /**
     * @param AddPageCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(PageTypePresentationCommand $command, Site $site)
    {
        $page = $this->pageRepository->get($command->id);
        if (!$page) {
            throw new \Exception(sprintf('Error - Page not found (id: %d)', $command->id));
        }
        $pageTypePresentation = $this->pageTypePresentationRepository->getByPageId($command->id);
        if (!$pageTypePresentation) {
            throw new \Exception(sprintf('Error - PageTypePresentation not found (id: %d)', $command->id));
        }
        $page->setType($command->type->getId());
        $page->setNameMenu($command->name);
        $page->setMenuHeaderOrder($command->menuHeaderOrder);
        $page->setMenuFooterOrder($command->menuFooterOrder);
        $page->setSeoTitle($command->seoTitle);
        $page->setSeoDescription($command->seoDescription);
        $this->pageRepository->save($page);
        $pageTypePresentation->setContent($command->content);
        $this->pageTypePresentationRepository->save($pageTypePresentation);
    }

}