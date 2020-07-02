<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Entity\Post;
use App\Enum\Constants;
use App\Helper\SiteHelper;
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
use App\Repository\PageRepository;
use App\Repository\PostRepository;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @Route("/admin/bo-type-blog")
 * @package App\Controller\Admin
 */
class PageTypeBlogController extends AbstractController
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var PageRepository */
    private $pageRepository;

    /** @var PostRepository */
    private $postRepository;

    /**
     * BlogController constructor.
     * @param SiteRepository $siteRepository
     * @param PageRepository $pageRepository
     * @param PostRepository $postRepository
     */
    public function __construct(SiteRepository $siteRepository, PageRepository $pageRepository, PostRepository $postRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->postRepository = $postRepository;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @Route("/{id}", name="bo-type-blog")
     * @param Request $request
     * @param EditPresentationCommandHandler $editPresentationCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boBlog(Request $request, $id = null, $success = false)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        $page = $this->pageRepository->getBySiteAndId($site, $id);
        $posts = $this->postRepository->getByPageId($id);

        return $this->render('admin/page/type-4/index.html.twig', [
            'site' => $site,
            'page' => $page,
            'blog' => $site->getBlog(),
            'posts' => $posts,
            'success' => $success
        ]);
    }

    /**
     * @Route("/post/add/{id}", name="bo-type-post-add")
     * @param Request $request
     * @param EditPostCommandHandler $editPostCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boAddBlog(Request $request, $id = null, EditPostCommandHandler $editPostCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        $page = $this->pageRepository->getBySiteAndId($site, $id);
        $posts = $this->postRepository->getByPageId($id);

        $command = new PostCommand();

        $success = false;

        $form = $this->createForm(EditPostType::class, $command, ['idSite' => SiteHelper::getuniqueKeySite($site)]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editPostCommandHandler->handle($command, $site, $page);
            $success = true;
            return $this->render('admin/page/type-4/index.html.twig', [
                'site' => $site,
                'page' => $page,
                'posts' => $posts,
                'success' => $success
            ]);
        }

        return $this->render('admin/page/type-4/add.html.twig', [
            'site' => $site,
            'page' => $page,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/post/{id}/edit/{idPage}", name="bo-type-post-edit")
     * @param Post $post
     * @param Request $request
     * @param EditPostCommandHandler $editPostCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boEditBlog(Post $post, Request $request, $idPage = null, EditPostCommandHandler $editPostCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        $page = $this->pageRepository->getBySiteAndId($site, $idPage);
        $posts = $this->postRepository->getByPageId($idPage);

        $command = new PostCommand();
        $command->id = $post->getId();
        $command->title = $post->getTitle();
        $command->introduction = $post->getIntroduction();
        $command->image = $post->getImage();
        $command->content = $post->getContent();

        $success = false;

        $form = $this->createForm(EditPostType::class, $command, ['idSite' => SiteHelper::getuniqueKeySite($site)]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editPostCommandHandler->handle($command, $site, $page);
            $success = true;
            return $this->render('admin/page/type-4/index.html.twig', [
                'site' => $site,
                'page' => $page,
                'posts' => $posts,
                'success' => $success
            ]);
        }

        return $this->render('admin/page/type-4/add.html.twig', [
            'site' => $site,
            'page' => $page,
            'post' => $post,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/post/{id}/remove/{idPage}", name="bo-type-post-remove")
     * @param Post $post
     * @param RemoveBlogCommandHandler $removeBlogCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Post $post, $idPage = null, RemoveBlogCommandHandler $removeBlogCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        $page = $this->pageRepository->getBySiteAndId($site, $idPage);
        $posts = $this->postRepository->getByPageId($idPage);
        $command = new RemoveBlogCommand();
        $command->id = $post->getId();

        try {
            $removeBlogCommandHandler->handle($command);
            $success = true;
        } catch (\Exception $e) {
            $success = false;
        }

        return $this->render('admin/page/type-4/index.html.twig', [
            'site' => $site,
            'page' => $page,
            'posts' => $posts,
            'success' => $success
        ]);
    }

}
