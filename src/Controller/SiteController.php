<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Site;
use App\Entity\Blog;
use App\Enum\ContactSubject;
use App\Izibrick\Command\AddTrackingContactCommand;
use App\Izibrick\Command\AddTrackingQuoteCommand;
use App\Izibrick\CommandHandler\AddTrackingContactCommandHandler;
use App\Izibrick\CommandHandler\AddTrackingQuoteCommandHandler;
use App\Form\AddTrackingContactType;
use App\Form\AddTrackingQuoteType;
use App\Repository\BlogRepository;
use App\Repository\CustomPageRepository;
use App\Repository\PageRepository;
use App\Repository\PostRepository;
use App\Repository\PricingRepository;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /** @var SiteRepository */
    private $siteRepository;
    /** @var PageRepository */
    private $pageRepository;
    /** @var CustomPageRepository */
    private $customPageRepository;
    /** @var BlogRepository */
    private $blogRepository;
    /** @var PostRepository */
    private $postRepository;
    /** @var PricingRepository */
    private $pricingRepository;

    /**
     * SiteController constructor.
     * @param SiteRepository $siteRepository
     * @param PageRepository $pageRepository
     * @param BlogRepository $blogRepository
     * @param PostRepository $postRepository
     * @param PricingRepository $pricingRepository
     */
    public function __construct(SiteRepository $siteRepository, PageRepository $pageRepository, CustomPageRepository $customPageRepository, BlogRepository $blogRepository, PostRepository $postRepository, PricingRepository $pricingRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->pageRepository = $pageRepository;
        $this->customPageRepository = $customPageRepository;
        $this->blogRepository = $blogRepository;
        $this->postRepository = $postRepository;
        $this->pricingRepository = $pricingRepository;
    }

    /**
     * @Route("/{name}/{post}/{postTitle}/",
     *     name="page_blog_detail",
     *     host="{nobackoffice}",
     *     requirements={"nobackoffice"="^((?!%base_host%).)*$"},
     *     defaults={"nobackoffice"=""}
     *     )
     *
     * @Route("/site/{siteName<.*>}/{name}/{post}/", name="site_page_blog_detail",
     *     host="%base_host%")
     * @Route("/site/{siteName<.*>}/{name}/{post}/{postTitle}/", name="site_page_blog_detail",
     *     host="%base_host%")
     */
    public function pageDetailBlog(Request $request, $siteName = null, $name = null, $post = null, $postTitle = null, AddTrackingContactCommandHandler $addTrackingContactCommandHandler, \Swift_Mailer $mailer)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByInternalName($siteName);
        } else {
            $site = $this->siteRepository->getByDomain($_SERVER['HTTP_HOST']);
        }//var_dump($siteName);die;
        $pages = $this->pageRepository->getAllHeaderBySiteId($site->getId());
        $pagesFooter = $this->pageRepository->getAllFooterBySiteId($site->getId());
        $customPage = $this->customPageRepository->getBySiteAndNameUrl($site, $name);
        $page = $this->pageRepository->getBySiteAndNameUrl($site, $name);
        // Page de type Blog
        $success = false;
        $postDetail = $this->postRepository->get($post);

        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/pages/type-'.$page->getType().'/detail.html.twig', [
            'controller_name' => 'SiteController' . $site->getName(),
            'site' => $site,
            'pages' => $pages,
            'pagesFooter' => $pagesFooter,
            'page' => $page,
            'post' => $postDetail
        ]);
    }

    /**
     * @Route("/{name}/",
     *     name="page",
     *     host="{nobackoffice}",
     *     requirements={"nobackoffice"="^((?!%base_host%).)*$"},
     *     defaults={"nobackoffice"=""}
     *     )
     * @Route("/site/{siteName<.*>}/{name}/", name="site_page",
     *     host="%base_host%")
     */
    public function page(Request $request, $siteName = null, $name = null, AddTrackingContactCommandHandler $addTrackingContactCommandHandler, \Swift_Mailer $mailer, PaginatorInterface $paginator)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByInternalName($siteName);
        } else {
            $site = $this->siteRepository->getByDomain($_SERVER['HTTP_HOST']);
        }//var_dump($siteName);die;
        $pages = $this->pageRepository->getAllHeaderBySiteId($site->getId());
        $pagesFooter = $this->pageRepository->getAllFooterBySiteId($site->getId());
        $customPage = $this->customPageRepository->getBySiteAndNameUrl($site, $name);
        $page = $this->pageRepository->getBySiteAndNameUrl($site, $name);
        if($page) {
            if ($page->getType() == 2) {
                // Page de type Présentation
                return $this->render('sites/template-' . $site->getTemplate()->getId() . '/pages/type-'.$page->getType().'/index.html.twig', [
                    'controller_name' => 'SiteController' . $site->getName(),
                    'site' => $site,
                    'pages' => $pages,
                    'pagesFooter' => $pagesFooter,
                    'page' => $page,
                    'success' => false,
                ]);
            } else if ($page->getType() == 3) {
                // Page de type Contact
                $success = false;
                $command = new AddTrackingContactCommand();

                $form = $this->createForm(AddTrackingContactType::class, $command);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $addTrackingContactCommandHandler->handle($command, $site);

                    $message = (new \Swift_Message('Demande de contact'))
                        ->setFrom($_ENV['SITE_MAILER_USER'])
                        ->setTo($site->getContact()->getEmail())
                        ->setReplyTo($command->getEmail())
                        ->setBody($this->renderView(
                            'sites/emails/contact.txt.twig',
                            ['command' => $command,
                                'site' => $site]
                        ), 'text/html'
                        );
                    $mailer->send($message);
                    $success = true;
                }
                return $this->render('sites/template-' . $site->getTemplate()->getId() . '/pages/type-'.$page->getType().'/index.html.twig', [
                    'controller_name' => 'SiteController' . $site->getName(),
                    'site' => $site,
                    'pages' => $pages,
                    'pagesFooter' => $pagesFooter,
                    'page' => $page,
                    'form' => $form->createView(),
                    'success' => false,
                ]);
            } else if ($page->getType() == 4) {
                // Page de type Blog
                $success = false;
                $posts = $this->postRepository->getByPageIdAndOrderByDateCreation($page->getId());
                $limitPagination = 10;
                if($page->getPagesTypeBlog()->getTemplate()->getId() == 8) {
                    $limitPagination = 12;
                }

                $pagination = $paginator->paginate(
                    $posts,
                    $request->query->getInt('page', 1), $limitPagination
                );


                return $this->render('sites/template-' . $site->getTemplate()->getId() . '/pages/type-'.$page->getType().'/index-'.$page->getPagesTypeBlog()->getTemplate()->getId().'.html.twig', [
                    'controller_name' => 'SiteController' . $site->getName(),
                    'site' => $site,
                    'pages' => $pages,
                    'pagesFooter' => $pagesFooter,
                    'page' => $page,
                    'posts' => $posts,
                    'pagination' => $pagination
                ]);
            }
        }
        if($customPage) {
            return $this->render('sites/template-' . $site->getTemplate()->getId() . '/custom-page/index.html.twig', [
                'controller_name' => 'SiteController' . $site->getName(),
                'site' => $site,
                'pages' => $pages,
                'pagesFooter' => $pagesFooter,
                'customPage' => $customPage
            ]);
        } else {
            return $this->render('sites/template-' . $site->getTemplate()->getId() . '/index/index.html.twig', [
                'site' => $site,
                'pages' => $pages,
                'pagesFooter' => $pagesFooter,
            ]);
        }
    }

    /**
     * @Route("/",
     *     name="home",
     *     host="{nobackoffice}",
     *     requirements={"nobackoffice"="^((?!%base_host%).)*$"},
     *     defaults={"nobackoffice"=""}
     *     )
     * @Route("/site/{siteName<.*>}", name="site_home",
     *     host="%base_host%")
     * @param string $siteName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function accueil(Request $request, $siteName = null, AddTrackingContactCommandHandler $addTrackingContactCommandHandler, \Swift_Mailer $mailer)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByInternalName($siteName);
        } else {
            $site = $this->siteRepository->getByDomain($_SERVER['HTTP_HOST']);
        }
        $pages = $this->pageRepository->getAllHeaderBySiteId($site->getId());
        $pagesFooter = $this->pageRepository->getAllFooterBySiteId($site->getId());
        $page = $this->pageRepository->get($site->getDefaultPage());

        if($page) {
            if ($page->getType() == 2) {//var_dump($page->getPagesTypePresentation()->getContent());die;
                // Page de type Présentation
                return $this->render('sites/template-' . $site->getTemplate()->getId() . '/pages/type-'.$page->getType().'/index.html.twig', [
                    'controller_name' => 'SiteController' . $site->getName(),
                    'site' => $site,
                    'pages' => $pages,
                    'pagesFooter' => $pagesFooter,
                    'page' => $page,
                    'success' => false,
                ]);
            } else if ($page->getType() == 3) {
                // Page de type Contact
                $success = false;
                $command = new AddTrackingContactCommand();

                $form = $this->createForm(AddTrackingContactType::class, $command);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $addTrackingContactCommandHandler->handle($command, $site);

                    $message = (new \Swift_Message('Demande de contact'))
                        ->setFrom($_ENV['SITE_MAILER_USER'])
                        ->setTo($site->getContact()->getEmail())
                        ->setReplyTo($command->getEmail())
                        ->setBody($this->renderView(
                            'sites/emails/contact.txt.twig',
                            ['command' => $command,
                                'site' => $site]
                        ), 'text/html'
                        );
                    $mailer->send($message);
                    $success = true;
                }
                return $this->render('sites/template-' . $site->getTemplate()->getId() . '/pages/type-'.$page->getType().'/index.html.twig', [
                    'controller_name' => 'SiteController' . $site->getName(),
                    'site' => $site,
                    'pages' => $pages,
                    'pagesFooter' => $pagesFooter,
                    'page' => $page,
                    'form' => $form->createView(),
                    'success' => false,
                ]);
            } else if ($page->getType() == 4) {
                // Page de type Blog
                $success = false;
                $posts = $this->postRepository->getByPageId($page->getId());

                return $this->render('sites/template-' . $site->getTemplate()->getId() . '/pages/type-'.$page->getType().'/index.html.twig', [
                    'controller_name' => 'SiteController' . $site->getName(),
                    'site' => $site,
                    'pages' => $pages,
                    'pagesFooter' => $pagesFooter,
                    'page' => $page,
                    'posts' => $posts
                ]);
            }
        } else {
            return $this->render('sites/template-' . $site->getTemplate()->getId() . '/no-result/index.html.twig', [
                'controller_name' => 'SiteController' . $site->getName(),
                'site' => $site,
                'pages' => null,
                'pagesFooter' => null,
                'page' => null
            ]);
        }
    }

}
