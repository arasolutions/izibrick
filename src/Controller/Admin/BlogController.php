<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Entity\Post;
use App\Izibrick\Command\ContactCommand;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Izibrick\Command\BlogCommand;
use App\Izibrick\Command\PostCommand;
use App\Izibrick\Command\RemoveBlogCommand;
use App\Izibrick\Command\HomeCommand;
use App\Izibrick\Command\PresentationCommand;
use App\Izibrick\CommandHandler\EditContactCommandHandler;
use App\Izibrick\CommandHandler\EditGlobalParametersCommandHandler;
use App\Izibrick\CommandHandler\EditBlogCommandHandler;
use App\Izibrick\CommandHandler\EditPostCommandHandler;
use App\Izibrick\CommandHandler\RemoveBlogCommandHandler;
use App\Izibrick\CommandHandler\EditHomeCommandHandler;
use App\Izibrick\CommandHandler\EditPresentationCommandHandler;
use App\Form\EditContactType;
use App\Form\EditGlobalParametersType;
use App\Form\EditBlogType;
use App\Form\EditHomeType;
use App\Form\EditPostType;
use App\Form\EditPresentationType;
use App\Form\AddSiteOptionsType;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @Route("/admin/bo-blog")
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
            'blog' => $site->getBlog(),
            'posts' => $site->getBlog()->getPosts(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/post/add", name="bo-post-add")
     * @param Request $request
     * @param EditPostCommandHandler $editPostCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boAddBlog(Request $request, EditPostCommandHandler $editPostCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

        $command = new PostCommand();

        $success = false;

        $form = $this->createForm(EditPostType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editPostCommandHandler->handle($command, $site);
            $success = true;
            return $this->render('admin/blog/index.html.twig', [
                'controller_name' => 'BlogController',
                'site' => $site,
                'posts' => $site->getBlog()->getPosts(),
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
     * @Route("/post/{id}/edit", name="bo-post-edit")
     * @param Post $post
     * @param Request $request
     * @param EditPostCommandHandler $editPostCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boEditBlog(Post $post, Request $request, EditPostCommandHandler $editPostCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

        $command = new PostCommand();
        $command->id = $post->getId();
        $command->title = $post->getTitle();
        $command->introduction = $post->getIntroduction();
        $command->image = $post->getImage();
        $command->content = $post->getContent();

        $success = false;

        $form = $this->createForm(EditPostType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editPostCommandHandler->handle($command, $site);
            $success = true;
            return $this->render('admin/blog/index.html.twig', [
                'controller_name' => 'BlogController',
                'site' => $site,
                'posts' => $site->getBlog()->getPosts(),
                'success' => $success
            ]);
        }

        return $this->render('admin/blog/add.html.twig', [
            'controller_name' => 'BlogController',
            'site' => $site,
            'post' => $post,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @param Blog $blog
     * @param RemoveBlogCommandHandler $handler
     * @return Response
     *
     * @Route("/post/{id}/remove", name="bo-post-remove")
     * @method ({"GET"})
     */
    public function remove(Blog $blog, RemoveBlogCommandHandler $handler)
    {
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);
        $command = new RemoveBlogCommand();
        $command->id = $blog->getId();

        try {
            $handler->handle($command);
            $success = true;
        } catch ( \Exception $e ) {
            $success = false;
        }

        return $this->render('admin/blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'site' => $site,
            'posts' => $site->getBlog()->getPosts(),
            'success' => $success
        ]);
    }
}
