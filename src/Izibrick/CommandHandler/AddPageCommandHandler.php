<?php

namespace App\Izibrick\CommandHandler;

use App\Entity\PageTypeBlog;
use App\Entity\PageTypeContact;
use App\Entity\PageTypePresentation;
use App\Entity\Site;
use App\Entity\Page;
use App\Helper\StringHelper;
use App\Izibrick\Command\AddPageCommand;
use App\Repository\PageTypeBlogRepository;
use App\Repository\PageTypeContactRepository;
use App\Repository\PageTypePresentationRepository;
use App\Repository\SiteRepository;
use App\Repository\PageRepository;
use App\Repository\TemplateRepository;

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

    /** @var TemplateRepository */
    private $templateRepository;

    /** @var PageTypePresentationRepository $pageTypePresentationRepository */
    private $pageTypePresentationRepository;

    /** @var PageTypeContactRepository $pageTypeContactRepository */
    private $pageTypeContactRepository;

    /** @var PageTypeBlogRepository $pageTypeBlogRepository */
    private $pageTypeBlogRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     * @param PageRepository $pageRepository
     * @param TemplateRepository $templateRepository
     * @param PageTypePresentationRepository $pageTypePresentationRepository
     * @param PageTypeContactRepository $pageTypeContactRepository
     * @param PageTypeBlogRepository $pageTypeBlogRepository
     */
    public function __construct(SiteRepository $siteRepository, PageRepository $pageRepository, TemplateRepository $templateRepository, PageTypePresentationRepository $pageTypePresentationRepository, PageTypeContactRepository $pageTypeContactRepository, PageTypeBlogRepository $pageTypeBlogRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->pageRepository = $pageRepository;
        $this->templateRepository = $templateRepository;
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
    public function handle(AddPageCommand $command, Site $site)
    {
        $page = new Page($site);

        $page->setType($command->type->getId());
        $page->setNameMenu($command->name);
        $page->setNameMenuUrl(StringHelper::cleanUrl($command->name));
        $page->setDisplayMenuHeader(true);
        $page->setDisplayMenuFooter(false);
        $this->pageRepository->save($page);
        if ($command->type->getId() == 2) {
            $pageTypePresentation = new PageTypePresentation($page);
            $this->pageTypePresentationRepository->save($pageTypePresentation);
        } else if ($command->type->getId() == 3) {
            $pageTypeContact = new PageTypeContact($page);
            $this->pageTypeContactRepository->save($pageTypeContact);
        } else if ($command->type->getId() == 4) {
            $pageTypeBlog = new PageTypeBlog($page);
            // On met un template par défaut
            $defaultTemplate = $this->templateRepository->get(7);
            $pageTypeBlog->setTemplate($defaultTemplate);
            $this->pageTypeBlogRepository->save($pageTypeBlog);
        }

    }

}