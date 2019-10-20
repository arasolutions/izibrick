<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Firebrock\Command\ContactCommand;
use App\Firebrock\Command\GlobalParametersCommand;
use App\Firebrock\Command\BlogCommand;
use App\Firebrock\Command\RemoveBlogCommand;
use App\Firebrock\Command\HomeCommand;
use App\Firebrock\Command\PresentationCommand;
use App\Firebrock\CommandHandler\EditContactCommandHandler;
use App\Firebrock\CommandHandler\EditGlobalParametersCommandHandler;
use App\Firebrock\CommandHandler\EditBlogCommandHandler;
use App\Firebrock\CommandHandler\RemoveBlogCommandHandler;
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
 * Class BlogController
 * @Route("/bo-blog")
 * @package App\Controller\Admin
 */
class BlogController extends AbstractController
{
    /** @var SiteRepository */
    private $siteRepository;

    /**
     * BlogController constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    /**
     * @Route("/", name="bo-blog")
     * @param Request $request
     * @param EditPresentationCommandHandler $editPresentationCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boBlog(Request $request, $success = false)
    {
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

        return $this->render('admin/blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'site' => $site,
            'blogs' => $site->getBlogs(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/add", name="bo-blog-add")
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
            return $this->render('admin/blog/index.html.twig', [
                'controller_name' => 'BlogController',
                'site' => $site,
                'blogs' => $site->getBlogs(),
                'success' => $success
            ]);
        }

        return $this->render('admin/blog/add.html.twig', [
            'controller_name' => 'BlogController',
            'site' => $site,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bo-blog-edit")
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
            return $this->render('admin/blog/index.html.twig', [
                'controller_name' => 'BlogController',
                'site' => $site,
                'blogs' => $site->getBlogs(),
                'success' => $success
            ]);
        }

        return $this->render('admin/blog/add.html.twig', [
            'controller_name' => 'BlogController',
            'site' => $site,
            'blog' => $blog,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @param Blog $blog
     * @param RemoveBlogCommandHandler $handler
     * @return Response
     *
     * @Route("/{id}/remove", name="bo-blog-remove")
     * @method ({"GET"})
     */
    public function remove(Blog $blog, RemoveBlogCommandHandler $handler)
    {
        $command = new RemoveBlogCommand();
        $command->id = $blog->getId();

        try {
            $handler->handle($command);
            $this->addFlash('success', 'Blog removed');
        } catch ( \Exception $e ) {
            $this->addFlash('error', sprintf('Impossible to remove blog : %s', $e->getMessage()));
        }

        return $this->redirectToRoute('bo-blog');
    }
}
