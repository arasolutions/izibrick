<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Firebrock\Command\ContactCommand;
use App\Firebrock\Command\GlobalParametersCommand;
use App\Firebrock\Command\BlogCommand;
use App\Firebrock\Command\HomeCommand;
use App\Firebrock\Command\PresentationCommand;
use App\Firebrock\CommandHandler\EditContactCommandHandler;
use App\Firebrock\CommandHandler\EditGlobalParametersCommandHandler;
use App\Firebrock\CommandHandler\EditBlogCommandHandler;
use App\Firebrock\CommandHandler\EditHomeCommandHandler;
use App\Firebrock\CommandHandler\EditPresentationCommandHandler;
use App\Form\EditContactType;
use App\Form\EditGlobalParametersType;
use App\Form\EditBlogType;
use App\Form\EditHomeType;
use App\Form\EditPresentationType;
use App\Form\AddSiteOptionsType;
use App\Repository\SiteRepository;
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

    /**
     * AdminController constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
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

        return $this->render('admin/dashboard/index.html.twig', [
            'controller_name' => 'AdminController',
            'site' => $userSite
        ]);
    }

    /**
     * @Route("/home", name="bo-home")
     * @param Request $request
     * @param EditHomeCommandHandler $editHomeCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function home(Request $request, EditHomeCommandHandler $editHomeCommandHandler)
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
     * @Route("/bo-blog", name="bo-blog")
     * @param Request $request
     * @param EditPresentationCommandHandler $editPresentationCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boBlog(Request $request, EditPresentationCommandHandler $editPresentationCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

        $command = new PresentationCommand();

        $success = false;

        $form = $this->createForm(EditPresentationType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editPresentationCommandHandler->handle($command, $site);
            $success = true;
        }

        return $this->render('admin/blog/index.html.twig', [
            'controller_name' => 'AdminController',
            'site' => $site,
            'blogs' => $site->getBlogs(),
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/bo-blog/add", name="bo-add-blog")
     * @param Request $request
     * @param EditBlogCommandHandler $editBlogCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boAddBlog(Request $request, EditBlogCommandHandler $editBlogCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

        $command = new BlogCommand();

        $success = false;

        $form = $this->createForm(EditBlogType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editBlogCommandHandler->handle($command, $site);
            $success = true;
        }

        return $this->render('admin/blog/add.html.twig', [
            'controller_name' => 'AdminController',
            'site' => $site,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/bo-blog/edit/{id}", name="bo-edit-blog")
     * @param Blog $blog
     * @param Request $request
     * @param EditBlogCommandHandler $editBlogCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boEditBlog(Blog $blog, Request $request, EditBlogCommandHandler $editBlogCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

        $command = new BlogCommand();
        $command->id = $blog->getId();
        $command->title = $blog->getTitle();
        $command->introduction = $blog->getIntroduction();
        $command->content = $blog->getContent();
        $command->originalContent = $blog->getContent();

        $success = false;

        $form = $this->createForm(EditBlogType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editBlogCommandHandler->handle($command, $site);
            $success = true;
        }

        return $this->render('admin/blog/add.html.twig', [
            'controller_name' => 'AdminController',
            'site' => $site,
            'blog' => $blog,
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
        $command->setEmail($site->getContact()->getEmail());

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
}
