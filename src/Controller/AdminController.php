<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Entity\Contact;
use App\Firebrock\Command\ContactCommand;
use App\Firebrock\Command\GlobalParametersCommand;
use App\Firebrock\Command\BlogCommand;
use App\Firebrock\Command\HomeCommand;
use App\Firebrock\Command\PresentationCommand;
use App\Firebrock\Command\QuoteCommand;
use App\Firebrock\Command\SeoCommand;
use App\Firebrock\CommandHandler\EditContactCommandHandler;
use App\Firebrock\CommandHandler\EditGlobalParametersCommandHandler;
use App\Firebrock\CommandHandler\EditBlogCommandHandler;
use App\Firebrock\CommandHandler\EditHomeCommandHandler;
use App\Firebrock\CommandHandler\EditPresentationCommandHandler;
use App\Firebrock\CommandHandler\EditQuoteCommandHandler;
use App\Firebrock\CommandHandler\EditSeoCommandHandler;
use App\Form\EditContactType;
use App\Form\EditGlobalParametersType;
use App\Form\EditBlogType;
use App\Form\EditHomeType;
use App\Form\EditPresentationType;
use App\Form\AddSiteOptionsType;
use App\Form\EditQuoteType;
use App\Form\EditSeoType;
use App\Repository\SiteRepository;
use App\Repository\HomeRepository;
use App\Repository\PresentationRepository;
use App\Repository\BlogRepository;
use App\Repository\QuoteRepository;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
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

    /** @var QuoteRepository $quoteRepository */
    private $quoteRepository;

    /** @var ContactRepository */
    private $contactRepository;

    /**
     * AdminController constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(SiteRepository $siteRepository,
                                HomeRepository $homeRepository,
                                PresentationRepository $presentationRepository,
                                BlogRepository $blogRepository,
                                QuoteRepository $quoteRepository,
                                ContactRepository $contactRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->homeRepository = $homeRepository;
        $this->presentationRepository = $presentationRepository;
        $this->blogRepository = $blogRepository;
        $this->quoteRepository = $quoteRepository;
        $this->contactRepository = $contactRepository;
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!isset($_SESSION["SITE_ID"])) {
            if (sizeof($user->getSites()) >= 1) {
                /** @var UserSite $userSite */
                $userSite = $user->getSites()[0];
                $_SESSION['SITE_ID'] = $userSite->getSite()->getId();
            }
        }

        $userSite = $this->siteRepository->getById($_SESSION['SITE_ID']);
        $home = $this->homeRepository->getBySiteId($_SESSION['SITE_ID']);
        $presentation = $this->presentationRepository->getBySiteId($_SESSION['SITE_ID']);
        $blog = $this->blogRepository->getBySiteId($_SESSION['SITE_ID']);
        $quote = $this->quoteRepository->getBySiteId($_SESSION['SITE_ID']);
        $contact = $this->contactRepository->getBySiteId($_SESSION['SITE_ID']);
        // Début Référencement
        $referencementTitle = 0;
        $referencementDescription = 0;
        $referencementTitleTaux = 0;
        $referencementDescriptionTaux = 0;
        $referencementTaux = 0;
        if ($home->getSeoTitle() != '') $referencementTitle++;
        if ($presentation->getSeoTitle() != '') $referencementTitle++;
        if ($blog->getSeoTitle() != '') $referencementTitle++;
        if ($quote->getSeoTitle() != '') $referencementTitle++;
        if ($contact->getSeoTitle() != '') $referencementTitle++;
        if ($home->getSeoDescription() != '') $referencementDescription++;
        if ($presentation->getSeoDescription() != '') $referencementDescription++;
        if ($blog->getSeoDescription() != '') $referencementDescription++;
        if ($quote->getSeoDescription() != '') $referencementDescription++;
        if ($contact->getSeoDescription() != '') $referencementDescription++;
        if ($referencementTitle != 0) $referencementTitleTaux = $referencementTitle / 5 * 100;
        if ($referencementDescription != 0) $referencementDescriptionTaux = $referencementTitle / 5 * 100;
        if ($referencementTitle != 0 || $referencementDescription != 0 ) $referencementTaux = ($referencementTitleTaux + $referencementDescriptionTaux) / 2;
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
            'controller_name' => 'AdminController',
            'site' => $userSite,
            'referencement' => $referencement,
            'nbrReseauxSociaux' => $nbrReseauxSociaux,
            'quotes' => $userSite->getTrackingQuotes(),
            'contacts' => $userSite->getTrackingContacts(),
            'posts' => $blog->getPosts()
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
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

        $command = new HomeCommand();
        $command->setOriginalContent($site->getHome()->getContent());

        $success = false;

        $form = $this->createForm(EditHomeType::class, $command);
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
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

        $command = new PresentationCommand();
        //$command->setContent($site->getPresentation()->getContent());

        $success = false;

        $form = $this->createForm(EditPresentationType::class, $command);
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
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

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
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

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

        $form = $this->createForm(EditContactType::class, $command);
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
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

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
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);
        $home = $this->homeRepository->getBySiteId($_SESSION['SITE_ID']);
        $presentation = $this->presentationRepository->getBySiteId($_SESSION['SITE_ID']);
        $blog = $this->blogRepository->getBySiteId($_SESSION['SITE_ID']);
        $quote = $this->quoteRepository->getBySiteId($_SESSION['SITE_ID']);
        $contact = $this->contactRepository->getBySiteId($_SESSION['SITE_ID']);

        $command = new SeoCommand();//var_dump($contact);die;
        $command->seoTitleHome = $home->getSeoTitle();
        $command->seoDescriptionHome = $home->getSeoDescription();
        $command->seoTitlePresentation = $presentation->getSeoTitle();
        $command->seoDescriptionPresentation = $presentation->getSeoDescription();
        $command->seoTitleBlog = $blog->getSeoTitle();
        $command->seoDescriptionBlog = $blog->getSeoDescription();
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
}
