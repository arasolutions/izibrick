<?php

namespace App\Izibrick\CommandHandler;

use App\Entity\Site;
use App\Entity\Page;
use App\Izibrick\Command\AddPageCommand;
use App\Repository\SiteRepository;
use App\Repository\PageRepository;

/**
 * Class AddSupportCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class AddPageCommandHandler
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var PageRepository */
    private $pageRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     * @param PageRepository $pageRepository
     */
    public function __construct(SiteRepository $siteRepository, PageRepository $pageRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @param AddPageCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(AddPageCommand $command, Site $site)
    {
        $page = new Page($site);

        $page->setType($command->type->getId());
        $page->setNameMenu($command->name);
        $this->pageRepository->save($page);
    }

}