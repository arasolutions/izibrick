<?php

namespace App\Izibrick\CommandHandler;

use App\Entity\Site;
use App\Entity\Page;
use App\Helper\StringHelper;
use App\Izibrick\Command\AddPageCommand;
use App\Izibrick\Command\PageTypeBlogCommand;
use App\Repository\PageTypeBlogRepository;
use App\Repository\SiteRepository;
use App\Repository\PageRepository;

/**
 * Class AddSupportCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class EditPageTypeBlogCommandHandler
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var PageRepository */
    private $pageRepository;

    /** @var PageTypeBlogRepository */
    private $pageTypeBlogRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     * @param PageRepository $pageRepository
     * @param PageTypeBlogRepository $pageTypeBlogRepository
     */
    public function __construct(SiteRepository $siteRepository, PageRepository $pageRepository, PageTypeBlogRepository $pageTypeBlogRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->pageRepository = $pageRepository;
        $this->pageTypeBlogRepository = $pageTypeBlogRepository;
    }

    /**
     * @param AddPageCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(PageTypeBlogCommand $command, Site $site)
    {
        $page = $this->pageRepository->get($command->id);
        if (!$page) {
            throw new \Exception(sprintf('Error - Page not found (id: %d)', $command->id));
        }
        $pageTypeBlog = $this->pageTypeBlogRepository->getByPageId($command->id);
        if (!$pageTypeBlog) {
            throw new \Exception(sprintf('Error - PageTypeBlog not found (id: %d)', $command->id));
        }
        $page->setType($command->type->getId());
        $page->setNameMenu($command->name);
        $page->setNameMenuUrl(StringHelper::cleanUrl($command->name));
        $page->setDisplayMenuHeader($command->displayMenuHeader);
        $page->setDisplayMenuFooter($command->displayMenuFooter);
        $page->setSeoTitle($command->seoTitle);
        $page->setSeoDescription($command->seoDescription);
        $this->pageRepository->save($page);
        $pageTypeBlog->setContent($command->content);
        $this->pageTypeBlogRepository->save($pageTypeBlog);
    }

}