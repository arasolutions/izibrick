<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\PricingCategory;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Entity\Contact;
use App\Enum\Constants;
use App\Form\AddPageType;
use App\Form\EditCustomPageType;
use App\Form\EditPageTypeContactType;
use App\Form\EditPageTypePresentationType;
use App\Helper\SiteHelper;
use App\Helper\ApiAnalyticsHelper;
use App\Izibrick\Command\AddPageCommand;
use App\Izibrick\Command\ContactCommand;
use App\Izibrick\Command\CustomPageCommand;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Izibrick\Command\BlogCommand;
use App\Izibrick\Command\HomeCommand;
use App\Izibrick\Command\PageTypeContactCommand;
use App\Izibrick\Command\PageTypePresentationCommand;
use App\Izibrick\Command\PresentationCommand;
use App\Izibrick\Command\QuoteCommand;
use App\Izibrick\Command\SeoCommand;
use App\Izibrick\CommandHandler\AddPageCommandHandler;
use App\Izibrick\CommandHandler\EditContactCommandHandler;
use App\Izibrick\CommandHandler\EditCustomPageCommandHandler;
use App\Izibrick\CommandHandler\EditGlobalParametersCommandHandler;
use App\Izibrick\CommandHandler\EditBlogCommandHandler;
use App\Izibrick\CommandHandler\EditHomeCommandHandler;
use App\Izibrick\CommandHandler\EditPageTypeContactCommandHandler;
use App\Izibrick\CommandHandler\EditPageTypePresentationCommandHandler;
use App\Izibrick\CommandHandler\EditPresentationCommandHandler;
use App\Izibrick\CommandHandler\EditQuoteCommandHandler;
use App\Izibrick\CommandHandler\EditSeoCommandHandler;
use App\Form\EditContactType;
use App\Form\EditGlobalParametersType;
use App\Form\EditBlogType;
use App\Form\EditHomeType;
use App\Form\EditPresentationType;
use App\Form\AddSiteOptionsType;
use App\Form\EditQuoteType;
use App\Form\EditSeoType;
use App\Izibrick\CommandHandler\RemovePageCommandHandler;
use App\Izibrick\CommandHandler\RemoveSiteCommandHandler;
use App\Repository\CustomPageRepository;
use App\Repository\PageRepository;
use App\Repository\FontRepository;
use App\Repository\PageTypeContactRepository;
use App\Repository\PageTypePresentationRepository;
use App\Repository\PageTypeRepository;
use App\Repository\PricingCategoryRepository;
use App\Repository\PricingProductRepository;
use App\Repository\PricingRepository;
use App\Repository\SiteRepository;
use App\Repository\HomeRepository;
use App\Repository\PresentationRepository;
use App\Repository\BlogRepository;
use App\Repository\QuoteRepository;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

/**
 * Class AdminController
 * @Route("/admin")
 * @package App\Controller
 */
class AdminController extends AbstractController
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var CustomPageRepository $customPageRepository */
    private $customPageRepository;

    /** @var PageRepository $pageRepository */
    private $pageRepository;

    /** @var PageTypeRepository $pageTypeRepository */
    private $pageTypeRepository;

    /** @var PageTypePresentationRepository $pageTypePresentationRepository */
    private $pageTypePresentationRepository;

    /** @var PageTypeContactRepository $pageTypeContactRepository */
    private $pageTypeContactRepository;

    /** @var HomeRepository $homeRepository */
    private $homeRepository;

    /** @var PresentationRepository $presentationRepository */
    private $presentationRepository;

    /** @var BlogRepository $blogRepository */
    private $blogRepository;

    /** @var PricingRepository $pricingRepository */
    private $pricingRepository;

    /** @var QuoteRepository $quoteRepository */
    private $quoteRepository;

    /** @var ContactRepository */
    private $contactRepository;

    /** @var PricingCategoryRepository */
    private $pricingCategoryRepository;

    /** @var PricingProductRepository */
    private $pricingProductRepository;

    /** @var FontRepository */
    private $fontRepository;

    /**
     * AdminController constructor.
     * @param SiteRepository $siteRepository
     * @param CustomPageRepository $customPageRepository
     * @param PageRepository $pageRepository
     * @param PageTypeRepository $pageTypeRepository
     * @param PageTypePresentationRepository $pageTypePresentationRepository
     * @param PageTypeContactRepository $pageTypeContactRepository
     * @param HomeRepository $homeRepository
     * @param PresentationRepository $presentationRepository
     * @param BlogRepository $blogRepository
     * @param PricingRepository $pricingRepository
     * @param QuoteRepository $quoteRepository
     * @param ContactRepository $contactRepository
     * @param PricingCategoryRepository $pricingCategoryRepository
     * @param PricingProductRepository $pricingProductRepository
     * @param FontRepository $fontRepository
     */
    public function __construct(SiteRepository $siteRepository, CustomPageRepository $customPageRepository, PageRepository $pageRepository, PageTypeRepository $pageTypeRepository, PageTypePresentationRepository $pageTypePresentationRepository, PageTypeContactRepository $pageTypeContactRepository, HomeRepository $homeRepository, PresentationRepository $presentationRepository, BlogRepository $blogRepository, PricingRepository $pricingRepository, QuoteRepository $quoteRepository, ContactRepository $contactRepository, PricingCategoryRepository $pricingCategoryRepository, PricingProductRepository $pricingProductRepository, FontRepository $fontRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->customPageRepository = $customPageRepository;
        $this->pageRepository = $pageRepository;
        $this->pageTypeRepository = $pageTypeRepository;
        $this->pageTypePresentationRepository = $pageTypePresentationRepository;
        $this->pageTypeContactRepository =  $pageTypeContactRepository;
        $this->homeRepository = $homeRepository;
        $this->presentationRepository = $presentationRepository;
        $this->blogRepository = $blogRepository;
        $this->pricingRepository = $pricingRepository;
        $this->quoteRepository = $quoteRepository;
        $this->contactRepository = $contactRepository;
        $this->pricingCategoryRepository = $pricingCategoryRepository;
        $this->pricingProductRepository = $pricingProductRepository;
        $this->fontRepository = $fontRepository;
    }


    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!isset($_SESSION[Constants::SESSION_SITE_ID])) {
            $sites = $user->getSites();
            if (sizeof($sites) == 0) {
                $request->getSession()->set(Security::AUTHENTICATION_ERROR, new AuthenticationException('Vous n\'avez aucun site actif.'));
                return $this->redirectToRoute('fos_user_security_login');
            }
            if (sizeof($sites) > 1) {
                // Retour au choix d'un site
                return $this->redirectToRoute('choose-site');
            }
            if (sizeof($sites) == 1) {
                /** @var UserSite $site */
                $site = $sites[0];
                $_SESSION[Constants::SESSION_SITE_ID] = $site->getSite()->getId();
            }
        }

        $userSite = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        // Début Réseaux sociaux
        $nbrReseauxSociaux = 0;
        if ($userSite->getFacebook() != '') $nbrReseauxSociaux++;
        if ($userSite->getTwitter() != '') $nbrReseauxSociaux++;
        if ($userSite->getInstagram() != '') $nbrReseauxSociaux++;
        // Fin Réseaux sociaux

        // Analytics
        $dataVisiteursUnique = '';
        $dataVisiteursRecurrent = '';
        if($userSite->getAnalyticsViewId()){
            $analytics = ApiAnalyticsHelper::initializeAnalytics();
            $dataVisiteursUnique = ApiAnalyticsHelper::getReportVisiteurUnique($analytics, $userSite->getAnalyticsViewId());
            $dataVisiteursRecurrent = ApiAnalyticsHelper::getReportVisiteurRecurrent($analytics, $userSite->getAnalyticsViewId());
        }

        return $this->render('admin/dashboard/index.html.twig', [
            'site' => $userSite,
            'referencement' => null,
            'nbrReseauxSociaux' => $nbrReseauxSociaux,
            'quotes' => $userSite->getTrackingQuotes(),
            'contacts' => $userSite->getTrackingContacts(),
            'dataVisiteursUnique' => $dataVisiteursUnique,
            'dataVisiteursRecurrent' => $dataVisiteursRecurrent,
        ]);
    }

    /**
     * @Route("/choose-site", name="choose-site")
     * @Route("/choose-site/{id}", name="choose-site-id")
     */
    public function chooseSite($id = null)
    {
        if ($id != null) {
            $_SESSION[Constants::SESSION_SITE_ID] = $id;
            return $this->redirectToRoute('dashboard');
        } else {
            unset($_SESSION[Constants::SESSION_SITE_ID]);
        }

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('bo/cross/choose_site.html.twig', [
            'sites' => $this->siteRepository->findAllActiveSiteByUser($user)
        ]);
    }

    /**
     * @Route("/global-parameters", name="bo-global-parameters")
     * @param Request $request
     * @param EditGlobalParametersCommandHandler $editGlobalParametersCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function globalParams(Request $request, EditGlobalParametersCommandHandler $editGlobalParametersCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);

        $command = new GlobalParametersCommand($site);

        $success = false;

        $form = $this->createForm(EditGlobalParametersType::class, $command, ['method' => 'POST', 'site' => $site->getId()]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editGlobalParametersCommandHandler->handle($command, $site);
            $success = true;
        }

        return $this->render('admin/global-parameters/index.html.twig', [
            'controller_name' => 'AdminController',
            'site' => $site,
            'form' => $form->createView(),
            'fonts' => $this->fontRepository->findAll(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/bo-site/{id}/remove", name="site-remove")
     * @param Request $request
     * @param int $id
     * @param RemoveSiteCommandHandler $commandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function siteRemove(Request $request, $id = null, RemoveSiteCommandHandler $commandHandler)
    {

        /** @var User $user */
        $user = $this->getUser();

        $site = $this->siteRepository->getById($id);
        //Suppression du site
        $commandHandler->handle($user, $site);

        // Déconnexion
        $this->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();

        return $this->redirectToRoute('site-removed');
    }

    /**
     * @Route("/siteRemoved", name="site-removed")
     * @param Request $request
     * @param int $id
     * @param RemoveSiteCommandHandler $commandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function siteRemoved(Request $request)
    {
        return $this->render('bo/cross/site_removed.html.twig', []);
    }

    /**
     * @Route("/page/{id}", name="bo-page")
     * @param Request $request
     * @param EditCustomPageCommandHandler $editCustomPageCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function Page(Request $request, $id = null, EditCustomPageCommandHandler $editCustomPageCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        $page = $this->pageRepository->getBySiteAndId($site, $id);

        // Type de page présentation
        if ($page->getType() == 1) {
            return $this->render('admin/page/type-1/index.html.twig', [
                'controller_name' => 'AdminController',
                'site' => $site,
                'fonts' => $this->fontRepository->findAll(),
                'success' => true
            ]);
        }
        /*$command = new CustomPageCommand($site);
        $command->id = $customPage->getId();
        $command->place = $customPage->getPlace();
        $command->nameMenu = $customPage->getNameMenu();
        $command->content = $customPage->getContent();
        $command->seoTitle = $customPage->getSeoTitle();
        $command->seoDescription = $customPage->getSeoDescription();

        $success = false;

        $form = $this->createForm(EditCustomPageType::class, $command, ['idSite' => SiteHelper::getuniqueKeySite($site)]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editCustomPageCommandHandler->handle($command, $site);
            $success = true;
        }

        return $this->render('admin/page/1/index.html.twig', [
            'controller_name' => 'AdminController',
            'site' => $site,
            'form' => $form->createView(),
            'fonts' => $this->fontRepository->findAll(),
            'success' => $success
        ]);*/
    }


    /**
     * @Route("/page-add", name="bo-page-add")
     * @param Request $request
     * @param AddPageCommandHandler $addPageCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function PageAdd(Request $request, AddPageCommandHandler $addPageCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        /** @var User $user */
        $user = $this->getUser();
        $success = false;

        $page = new AddPageCommand();

        $form = $this->createForm(AddPageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addPageCommandHandler->handle($page, $site);
            $success = true;
        }

        return $this->render('admin/page/add.html.twig', [
            'form' => $form->createView(),
            'site' => $site,
            'success' => $success
        ]);
    }

    /**
     * @Route("/page-presentation-edit/{id}", name="bo-page-presentation-edit")
     * @param Request $request
     * @param EditPageTypePresentationCommandHandler $editPageTypePresentationCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function PagePresentationEdit(Request $request, $id = null, EditPageTypePresentationCommandHandler $editPageTypePresentationCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        /** @var User $user */
        $user = $this->getUser();
        $success = false;
        $page = $this->pageRepository->getBySiteAndId($site, $id);
        $typePage = $this->pageTypeRepository->get($page->getType());
        $pageTypePresentation = $this->pageTypePresentationRepository->getByPageId($id);
        $pageTypeCommand = new PageTypePresentationCommand();
        $pageTypeCommand->id = $page->getId();
        $pageTypeCommand->name = $page->getNameMenu();
        $pageTypeCommand->displayMenuHeader = $page->getDisplayMenuHeader();
        $pageTypeCommand->displayMenuFooter = $page->getDisplayMenuFooter();
        $pageTypeCommand->seoTitle = $page->getSeoTitle();
        $pageTypeCommand->seoDescription = $page->getSeoDescription();
        $pageTypeCommand->type = $typePage;
        // Spécifique page Présentation
        $pageTypeCommand->content = $pageTypePresentation->getContent();

        $form = $this->createForm(EditPageTypePresentationType::class, $pageTypeCommand, ['idSite' => SiteHelper::getuniqueKeySite($site)]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $editPageTypePresentationCommandHandler->handle($pageTypeCommand, $site);
            $success = true;
        }
        return $this->render('admin/page/type-2/index.html.twig', [
            'controller_name' => 'AdminController',
            'site' => $site,
            'page' => $page,
            'form' => $form->createView(),
            'success' => $success,
        ]);
    }

    /**
     * @Route("/page-remove/{id}", name="bo-page-remove")
     * @param Request $request
     * @param RemovePageCommandHandler $removePageCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function PageRemove(Request $request, $id = null, RemovePageCommandHandler $removePageCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        $removePageCommandHandler->handle($id);
        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/page-contact-edit/{id}", name="bo-page-contact-edit")
     * @param Request $request
     * @param EditPageTypeContactCommandHandler $editPageTypeContactCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function PageContactEdit(Request $request, $id = null, EditPageTypeContactCommandHandler $editPageTypeContactCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        /** @var User $user */
        $user = $this->getUser();
        $success = false;
        $page = $this->pageRepository->getBySiteAndId($site, $id);
        $typePage = $this->pageTypeRepository->get($page->getType());
        $pageTypeContact = $this->pageTypeContactRepository->getByPageId($id);
        $pageTypeCommand = new PageTypeContactCommand();
        $pageTypeCommand->id = $page->getId();
        $pageTypeCommand->name = $page->getNameMenu();
        $pageTypeCommand->displayMenuHeader = $page->getDisplayMenuHeader();
        $pageTypeCommand->displayMenuFooter = $page->getDisplayMenuFooter();
        $pageTypeCommand->seoTitle = $page->getSeoTitle();
        $pageTypeCommand->seoDescription = $page->getSeoDescription();
        $pageTypeCommand->type = $typePage;
        // Spécifique page Contact
        $pageTypeCommand->content = $pageTypeContact->getContent();
        $pageTypeCommand->presentation = $pageTypeContact->getPresentation();
        $pageTypeCommand->email = $pageTypeContact->getEmail();
        $pageTypeCommand->phone = $pageTypeContact->getPhone();
        $pageTypeCommand->nameAddress = $pageTypeContact->getName();
        $pageTypeCommand->postCode = $pageTypeContact->getPostCode();
        $pageTypeCommand->city = $pageTypeContact->getCity();
        $pageTypeCommand->country = $pageTypeContact->getCountry();
        $pageTypeCommand->openingTime = $pageTypeContact->getOpeningTime();

        $form = $this->createForm(EditPageTypeContactType::class, $pageTypeCommand, ['idSite' => SiteHelper::getuniqueKeySite($site)]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $editPageTypeContactCommandHandler->handle($pageTypeCommand, $site);
            $success = true;
        }
        return $this->render('admin/page/type-3/index.html.twig', [
            'controller_name' => 'AdminController',
            'site' => $site,
            'page' => $page,
            'form' => $form->createView(),
            'success' => $success,
        ]);
    }
}
