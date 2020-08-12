<?php

namespace App\Izibrick\CommandHandler;

use App\Entity\PageTypeBlog;
use App\Entity\PageTypeContact;
use App\Entity\PageTypePresentation;
use App\Entity\Site;
use App\Entity\Page;
use App\Helper\StringHelper;
use App\Izibrick\Command\AddPageCommand;
use App\Izibrick\Command\AddTemplatePagesCommand;
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
class AddTemplatePagesCommandHandler
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
     * @param AddTemplatePagesCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(AddTemplatePagesCommand $command, Site $site)
    {
        // Page d'accueil
        $pageAccueil = new Page($site);
        $pageAccueil->setType(2);
        $pageAccueil->setNameMenu('Accueil');
        $pageAccueil->setNameMenuUrl(StringHelper::cleanUrl($site->getName()));
        $pageAccueil->setDisplayMenuHeader(false);
        $pageAccueil->setDisplayMenuFooter(false);
        $this->pageRepository->save($pageAccueil);
        $pageTypeAccueil = new PageTypePresentation($pageAccueil);
        $pageTypeAccueil->setContent('Page accueil');
        $this->pageTypePresentationRepository->save($pageTypeAccueil);

        // Page de présentation
        $pagePresentation = new Page($site);
        $pagePresentation->setType(2);
        $pagePresentation->setNameMenu('Presentation');
        $pagePresentation->setNameMenuUrl('presentation');
        $pageAccueil->setMenuHeaderOrder(1);
        $pagePresentation->setDisplayMenuHeader(true);
        $pagePresentation->setDisplayMenuFooter(false);
        $this->pageRepository->save($pagePresentation);
        $pageTypePresentation = new PageTypePresentation($pagePresentation);
        $pageTypePresentation->setContent('Page Presentation');
        $this->pageTypePresentationRepository->save($pageTypePresentation);

        // Page de blog
        $pageBlog = new Page($site);
        $pageBlog->setType(4);
        $pageBlog->setNameMenu('Blog');
        $pageBlog->setNameMenuUrl('blog');
        $pageAccueil->setMenuHeaderOrder(2);
        $pageBlog->setDisplayMenuHeader(true);
        $pageBlog->setDisplayMenuFooter(false);
        $this->pageRepository->save($pageBlog);
        $pageTypeBlog = new PageTypeBlog($pageBlog);
        $pageTypeBlog->setContent('Page Blog');
        $defaultTemplate = $this->templateRepository->get(7);
        $pageTypeBlog->setTemplate($defaultTemplate);
        $this->pageTypeBlogRepository->save($pageTypeBlog);

        // Page de tarif
        if ($command->choice == 1) {
            $pageTarif = new Page($site);
            $pageTarif->setType(2);
            $pageTarif->setNameMenu('Tarif');
            $pageTarif->setNameMenuUrl('tarif');
            $pageAccueil->setMenuHeaderOrder(3);
            $pageTarif->setDisplayMenuHeader(true);
            $pageTarif->setDisplayMenuFooter(false);
            $this->pageRepository->save($pageTarif);
            $pageTypeTarif = new PageTypePresentation($pageTarif);
            $pageTypeTarif->setContent('Page Tarif');
            $this->pageTypePresentationRepository->save($pageTypeTarif);
        }

        // Page de contact
        $pageContact = new Page($site);
        $pageContact->setType(3);
        $pageContact->setNameMenu('Contact');
        $pageContact->setNameMenuUrl('contact');
        $pageAccueil->setMenuHeaderOrder(4);
        $pageContact->setDisplayMenuHeader(true);
        $pageContact->setDisplayMenuFooter(false);
        $this->pageRepository->save($pageContact);
        $pageTypeContact = new PageTypeContact($pageContact);
        $pageTypeContact->setContent('Page Contact');
        $this->pageTypeContactRepository->save($pageTypeContact);

        $site->setDefaultPage($pageAccueil);
        $this->siteRepository->save($site);

    }

}