<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Site;
use App\Entity\Blog;
use App\Izibrick\Command\AddTrackingContactCommand;
use App\Izibrick\Command\AddTrackingQuoteCommand;
use App\Izibrick\CommandHandler\AddTrackingContactCommandHandler;
use App\Izibrick\CommandHandler\AddTrackingQuoteCommandHandler;
use App\Form\AddTrackingContactType;
use App\Form\AddTrackingQuoteType;
use App\Repository\BlogRepository;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /** @var SiteRepository */
    private $siteRepository;
    /** @var BlogRepository */
    private $blogRepository;

    /**
     * SiteController constructor.
     * @param SiteRepository $siteRepository
     * @param BlogRepository $blogRepository
     */
    public function __construct(SiteRepository $siteRepository, BlogRepository $blogRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->blogRepository = $blogRepository;
    }

    /**
     * @Route("/presentation",
     *     name="presentation",
     *     host="{nobackoffice}",
     *     requirements={"nobackoffice"="^((?!%base_host%).)*$"},
     *     defaults={"nobackoffice"=""}
     *     )
     * @Route("/site/{siteName<.*>}/presentation", name="site_presentation",
     *     host="%base_host%")
     */
    public function presentation($siteName = null)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByName($siteName);
        } else {
            $site = $this->siteRepository->getByDomain($_SERVER['HTTP_HOST']);
        }

        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/presentation/index.html.twig', [
            'controller_name' => 'SiteController' . $site->getName(),
            'site' => $site
        ]);
    }

    /**
     * @Route("/blog",
     *     name="blog",
     *     host="{nobackoffice}",
     *     requirements={"nobackoffice"="^((?!%base_host%).)*$"},
     *     defaults={"nobackoffice"=""}
     *     )
     * @Route("/site/{siteName<.*>}/blog", name="site_blog",
     *     host="%base_host%")
     */
    public function blog($siteName = null)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByName($siteName);
        } else {
            $site = $this->siteRepository->getByDomain($_SERVER['HTTP_HOST']);
        }

        $blog = $this->blogRepository->getBySiteId($site->getId());

        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/blog/index.html.twig', [
            'controller_name' => 'SiteController' . $site->getName(),
            'site' => $site,
            'blog' => $blog,
            'posts' => $blog->getPosts()
        ]);
    }

    /**
     * @Route("/blog/{post}",
     *     name="blog_detail",
     *     host="{nobackoffice}",
     *     requirements={"nobackoffice"="^((?!%base_host%).)*$"},
     *     defaults={"nobackoffice"=""}
     *     )
     * @Route("/site/{siteName<.*>}/blog/{post}", name="site_blog_detail",
     *     host="%base_host%")
     * @param Post $post
     * @param null $siteName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function blogDetail(Post $post, $siteName = null)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByName($siteName);
        } else {
            $site = $this->siteRepository->getByDomain($_SERVER['HTTP_HOST']);
        }


        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/blog/detail.html.twig', [
            'controller_name' => 'SiteController' . $site->getName(),
            'site' => $site,
            'post' => $post
        ]);
    }

    /**
     * @Route("/devis",
     *     name="devis",
     *     host="{nobackoffice}",
     *     requirements={"nobackoffice"="^((?!%base_host%).)*$"},
     *     defaults={"nobackoffice"=""}
     *     )
     * @Route("/site/{siteName<.*>}/devis", name="site_devis",
     *     host="%base_host%")
     * @param Request $request
     * @param $siteName
     * @param AddTrackingQuoteCommandHandler $addTrackingQuoteCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function devis(Request $request, $siteName = null, AddTrackingQuoteCommandHandler $addTrackingQuoteCommandHandler)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByName($siteName);
        } else {
            $site = $this->siteRepository->getByDomain($_SERVER['HTTP_HOST']);
        }
        $success = false;
        $command = new AddTrackingQuoteCommand();

        $form = $this->createForm(AddTrackingQuoteType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $addTrackingQuoteCommandHandler->handle($command, $site);
            $success = true;
        }

        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/devis/index.html.twig', [
            'controller_name' => 'SiteController' . $site->getName(),
            'site' => $site,
            'form' => $form->createView(),
            'quote' => $site->getQuote(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/contact",
     *     name="contact",
     *     host="{nobackoffice}",
     *     requirements={"nobackoffice"="^((?!%base_host%).)*$"},
     *     defaults={"nobackoffice"=""}
     *     )
     * @Route("/site/{siteName<.*>}/contact", name="site_contact",
     *     host="%base_host%")
     * @param Request $request
     * @param $siteName
     * @param AddTrackingContactCommandHandler $addTrackingContactCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function contact(Request $request,
                            $siteName = null,
                            AddTrackingContactCommandHandler $addTrackingContactCommandHandler)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByName($siteName);
        } else {
            $site = $this->siteRepository->getByDomain($_SERVER['HTTP_HOST']);
        }
        $success = false;
        $command = new AddTrackingContactCommand();

        $form = $this->createForm(AddTrackingContactType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $addTrackingContactCommandHandler->handle($command, $site);
            $success = true;
        }

        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/contact/index.html.twig', [
            'site' => $site,
            'form' => $form->createView(),
            'contact' => $site->getContact(),
            'success' => $success
        ]);
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
    public function accueil($siteName = null)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByName($siteName);
        } else {
            $site = $this->siteRepository->getByDomain($_SERVER['HTTP_HOST']);
        }

        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/index/index.html.twig', [
            'site' => $site
        ]);
    }

}
