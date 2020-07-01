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
class RemovePageCommandHandler
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
        }
        $this->pageRepository->remove($page);
    }

}