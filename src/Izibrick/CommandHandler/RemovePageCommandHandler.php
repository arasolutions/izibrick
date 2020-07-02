<?php

namespace App\Izibrick\CommandHandler;

use App\Entity\Site;
use App\Entity\Page;
use App\Izibrick\Command\AddPageCommand;
use App\Izibrick\Command\PageTypePresentationCommand;
use App\Repository\PageTypeBlogRepository;
use App\Repository\PageTypeContactRepository;
use App\Repository\PageTypePresentationRepository;
use App\Repository\SiteRepository;
use App\Repository\PageRepository;

/**
 * Class AddSupportCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class RemovePageCommandHandler
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var PageRepository */
    private $pageRepository;

    /** @var PageTypePresentationRepository */
    private $pageTypePresentationRepository;

    /** @var PageTypeContactRepository $pageTypeContactRepository */
    private $pageTypeContactRepository;

    /** @var PageTypeBlogRepository $pageTypeBlogRepository */
    private $pageTypeBlogRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     * @param PageRepository $pageRepository
     * @param PageTypePresentationRepository $pageTypePresentationRepository
     * @param PageTypeContactRepository $pageTypeContactRepository
     * @param PageTypeBlogRepository $pageTypeBlogRepository
     */
    public function __construct(SiteRepository $siteRepository, PageRepository $pageRepository, PageTypePresentationRepository $pageTypePresentationRepository, PageTypeContactRepository $pageTypeContactRepository, PageTypeBlogRepository $pageTypeBlogRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->pageRepository = $pageRepository;
        $this->pageTypePresentationRepository = $pageTypePresentationRepository;
        $this->pageTypeContactRepository =  $pageTypeContactRepository;
        $this->pageTypeBlogRepository = $pageTypeBlogRepository;
    }

    /**
     * @param AddPageCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle($id)
    {
        $page = $this->pageRepository->get($id);
        if (!$page) {
            throw new \Exception(sprintf('Error - Page not found (id: %d)', $id));
        }
        if($page->getType() == 2) {
            $pageTypePresentation = $this->pageTypePresentationRepository->getByPageId($id);
            if (!$pageTypePresentation) {
                throw new \Exception(sprintf('Error - PageTypePresentation not found (id: %d)', $id));
            }
            $this->pageTypePresentationRepository->remove($pageTypePresentation);
        } else if($page->getType() == 3) {
            $pageTypeContact = $this->pageTypeContactRepository->getByPageId($id);
            if (!$pageTypeContact) {
                throw new \Exception(sprintf('Error - PageTypeContact not found (id: %d)', $id));
            }
            $this->pageTypeContactRepository->remove($pageTypeContact);
        } else if($page->getType() == 4) {
            $pageTypeBlog = $this->pageTypeBlogRepository->getByPageId($id);
            if (!$pageTypeBlog) {
                throw new \Exception(sprintf('Error - PageTypeBlog not found (id: %d)', $id));
            }
            $this->pageTypeBlogRepository->remove($pageTypeBlog);
        }
        $this->pageRepository->remove($page);
    }

}