<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\PricingCategory;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Entity\Contact;
use App\Enum\Constants;
use App\Helper\SiteHelper;
use App\Izibrick\Command\ContactCommand;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Izibrick\Command\BlogCommand;
use App\Izibrick\Command\HomeCommand;
use App\Izibrick\Command\PresentationCommand;
use App\Izibrick\Command\QuoteCommand;
use App\Izibrick\Command\SeoCommand;
use App\Izibrick\CommandHandler\EditContactCommandHandler;
use App\Izibrick\CommandHandler\EditGlobalParametersCommandHandler;
use App\Izibrick\CommandHandler\EditBlogCommandHandler;
use App\Izibrick\CommandHandler\EditHomeCommandHandler;
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
use App\Izibrick\CommandHandler\RemoveSiteCommandHandler;
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

    /**
     * AdminController constructor.
     * @param SiteRepository $siteRepository
     * @param HomeRepository $homeRepository
     * @param PresentationRepository $presentationRepository
     * @param BlogRepository $blogRepository
     * @param PricingRepository $pricingRepository
     * @param QuoteRepository $quoteRepository
     * @param ContactRepository $contactRepository
     * @param PricingCategoryRepository $pricingCategoryRepository
     * @param PricingProductRepository $pricingProductRepository
     */
    public function __construct(SiteRepository $siteRepository,
                                HomeRepository $homeRepository,
                                PresentationRepository $presentationRepository,
                                BlogRepository $blogRepository,
                                PricingRepository $pricingRepository,
                                QuoteRepository $quoteRepository,
                                ContactRepository $contactRepository,
                                PricingCategoryRepository $pricingCategoryRepository,
                                PricingProductRepository $pricingProductRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->homeRepository = $homeRepository;
        $this->presentationRepository = $presentationRepository;
        $this->blogRepository = $blogRepository;
        $this->pricingRepository = $pricingRepository;
        $this->quoteRepository = $quoteRepository;
        $this->contactRepository = $contactRepository;
        $this->pricingCategoryRepository = $pricingCategoryRepository;
        $this->pricingProductRepository = $pricingProductRepository;
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
        $home = $this->homeRepository->getBySiteId($_SESSION[Constants::SESSION_SITE_ID]);
        $presentation = $this->presentationRepository->getBySiteId($_SESSION[Constants::SESSION_SITE_ID]);
        $blog = $this->blogRepository->getBySiteId($_SESSION[Constants::SESSION_SITE_ID]);
        $pricing = $this->pricingRepository->getBySiteId($_SESSION[Constants::SESSION_SITE_ID]);
        $quote = $this->quoteRepository->getBySiteId($_SESSION[Constants::SESSION_SITE_ID]);
        $contact = $this->contactRepository->getBySiteId($_SESSION[Constants::SESSION_SITE_ID]);
        // Début Référencement
        $referencementTitle = 0;
        $referencementDescription = 0;
        $referencementTitleTaux = 0;
        $referencementDescriptionTaux = 0;
        $referencementTaux = 0;
        $nbrPages = 4;
        if ($home->getSeoTitle() != '') $referencementTitle++;
        if ($presentation->getSeoTitle() != '') $referencementTitle++;
        if ($blog->getSeoTitle() != '') $referencementTitle++;
        if($pricing->getDisplay() == true){
            if ($pricing->getSeoTitle() != '') $referencementTitle++;
            $nbrPages ++;
        }
        if($quote->getDisplay() == true){
            if ($quote->getSeoTitle() != '') $referencementTitle++;
            $nbrPages ++;
        }
        if ($contact->getSeoTitle() != '') $referencementTitle++;
        if ($home->getSeoDescription() != '') $referencementDescription++;
        if ($presentation->getSeoDescription() != '') $referencementDescription++;
        if ($blog->getSeoDescription() != '') $referencementDescription++;
        if($pricing->getDisplay() == true){
            if ($pricing->getSeoDescription() != '') $referencementDescription++;
        }
        if($quote->getDisplay() == true){
            if ($quote->getSeoDescription() != '') $referencementDescription++;
        }
        if ($contact->getSeoDescription() != '') $referencementDescription++;
        if ($referencementTitle != 0) $referencementTitleTaux = $referencementTitle / $nbrPages * 100;
        if ($referencementDescription != 0) $referencementDescriptionTaux = $referencementDescription / $nbrPages * 100;
        if ($referencementTitle != 0 || $referencementDescription != 0) $referencementTaux = ($referencementTitleTaux + $referencementDescriptionTaux) / 2;
        $referencement = array(
            'referencementTitleTaux' => $referencementTitleTaux,
            'referencementDescriptionTaux' => $referencementDescriptionTaux,
            'referencementTaux' => $referencementTaux
        );
        // Fin Référencement
        // Début Réseaux sociaux
        $nbrReseauxSociaux = 0;
        if ($userSite->getFacebook() != '') $nbrReseauxSociaux++;
        if ($userSite->getTwitter() != '') $nbrReseauxSociaux++;
        if ($userSite->getInstagram() != '') $nbrReseauxSociaux++;
        // Fin Réseaux sociaux

        return $this->render('admin/dashboard/index.html.twig', [
            'site' => $userSite,
            'referencement' => $referencement,
            'nbrReseauxSociaux' => $nbrReseauxSociaux,
            'quotes' => $userSite->getTrackingQuotes(),
            'contacts' => $userSite->getTrackingContacts(),
            'posts' => $blog->getPosts()
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
     * @Route("/bo-home", name="bo-home")
     * @param Request $request
     * @param EditHomeCommandHandler $editHomeCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boHome(Request $request, EditHomeCommandHandler $editHomeCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);

        $command = new HomeCommand();
        $command->setOriginalContent($site->getHome()->getContent());
        $command->setTextPicture($site->getHome()->getTextPicture());
        $command->setContent($site->getHome()->getContent());

        $success = false;

        $form = $this->createForm(EditHomeType::class, $command, ['idSite' => SiteHelper::getuniqueKeySite($site)]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $editHomeCommandHandler->handle($command, $site);
            $success = true;
        }

        return $this->render('admin/accueil/index.html.twig', [
            'controller_name' => 'AdminController',
            'site' => $site,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/bo-presentation", name="bo-presentation")
     * @param Request $request
     * @param EditPresentationCommandHandler $editPresentationCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boPresentation(Request $request, EditPresentationCommandHandler $editPresentationCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);

        $command = new PresentationCommand();
        $command->setContent($site->getPresentation()->getContent());

        $success = false;

        $form = $this->createForm(EditPresentationType::class, $command, ['idSite' => SiteHelper::getuniqueKeySite($site)]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editPresentationCommandHandler->handle($command, $site);
            $success = true;
        }

        return $this->render('admin/presentation/index.html.twig', [
            'controller_name' => 'AdminController',
            'site' => $site,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/bo-quote", name="bo-quote")
     * @param Request $request
     * @param EditQuoteCommandHandler $editQuoteCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boQuote(Request $request, EditQuoteCommandHandler $editQuoteCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);

        $command = new QuoteCommand();
        $command->presentation = $site->getQuote()->getPresentation();
        $command->email = $site->getQuote()->getEmail();

        $success = false;

        $form = $this->createForm(EditQuoteType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editQuoteCommandHandler->handle($command, $site);
            $success = true;
        }

        return $this->render('admin/quote/index.html.twig', [
            'controller_name' => 'AdminController',
            'site' => $site,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/bo-contact", name="bo-contact")
     * @param Request $request
     * @param EditContactCommandHandler $editContactCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boContact(Request $request, EditContactCommandHandler $editContactCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);

        $command = new ContactCommand();
        $command->presentation = $site->getContact()->getPresentation();
        $command->email = $site->getContact()->getEmail();
        $command->phone = $site->getContact()->getPhone();
        $command->name = $site->getContact()->getName();
        $command->postCode = $site->getContact()->getPostCode();
        $command->city = $site->getContact()->getCity();
        $command->country = $site->getContact()->getCountry();
        $command->openingTime = $site->getContact()->getOpeningTime();

        $success = false;

        $form = $this->createForm(EditContactType::class, $command, ['idSite' => SiteHelper::getuniqueKeySite($site)]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editContactCommandHandler->handle($command, $site);
            $success = true;
        }

        return $this->render('admin/contact/index.html.twig', [
            'controller_name' => 'AdminController',
            'site' => $site,
            'form' => $form->createView(),
            'success' => $success
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

        $form = $this->createForm(EditGlobalParametersType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editGlobalParametersCommandHandler->handle($command, $site);
            $success = true;
        }

        return $this->render('admin/global-parameters/index.html.twig', [
            'controller_name' => 'AdminController',
            'site' => $site,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }


    /**
     * @Route("/bo-seo", name="bo-seo")
     * @param Request $request
     * @param EditSeoCommandHandler $editSeoCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boSeo(Request $request, EditSeoCommandHandler $editSeoCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        $home = $this->homeRepository->getBySiteId($_SESSION[Constants::SESSION_SITE_ID]);
        $presentation = $this->presentationRepository->getBySiteId($_SESSION[Constants::SESSION_SITE_ID]);
        $blog = $this->blogRepository->getBySiteId($_SESSION[Constants::SESSION_SITE_ID]);
        $pricing = $this->pricingRepository->getBySiteId($_SESSION[Constants::SESSION_SITE_ID]);
        $quote = $this->quoteRepository->getBySiteId($_SESSION[Constants::SESSION_SITE_ID]);
        $contact = $this->contactRepository->getBySiteId($_SESSION[Constants::SESSION_SITE_ID]);

        $command = new SeoCommand();//var_dump($contact);die;
        $command->seoTitleHome = $home->getSeoTitle();
        $command->seoDescriptionHome = $home->getSeoDescription();
        $command->seoTitlePresentation = $presentation->getSeoTitle();
        $command->seoDescriptionPresentation = $presentation->getSeoDescription();
        $command->seoTitleBlog = $blog->getSeoTitle();
        $command->seoDescriptionBlog = $blog->getSeoDescription();
        $command->seoTitlePricing = $pricing->getSeoTitle();
        $command->seoDescriptionPricing = $pricing->getSeoDescription();
        $command->seoTitleQuote = $quote->getSeoTitle();
        $command->seoDescriptionQuote = $quote->getSeoDescription();
        $command->seoTitleContact = $contact->getSeoTitle();
        $command->seoDescriptionContact = $contact->getSeoDescription();

        $success = false;

        $form = $this->createForm(EditSeoType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editSeoCommandHandler->handle($command, $site);
            $success = true;
        }

        return $this->render('admin/seo/index.html.twig', [
            'controller_name' => 'AdminController',
            'site' => $site,
            'form' => $form->createView(),
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

}
