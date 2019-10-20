<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\Blog;
use App\Repository\BlogRepository;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/presentation", name="presentation")
     */
    public function presentation($siteName = null)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByName($siteName);
        } else {
            $site = $this->siteRepository->getById(apache_getenv('SITE_ID'));
        }

        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/presentation/index.html.twig', [
            'controller_name' => 'SiteController' . $site->getName(),
            'site' => $site
        ]);
    }

    /**
     * @Route("/blog", name="blog")
     */
    public function blog($siteName = null)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByName($siteName);
        } else {
            $site = $this->siteRepository->getById(apache_getenv('SITE_ID'));
        }
        $blog = $this->blogRepository->getBySiteId($site->getId());

        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/blog/index.html.twig', [
            'controller_name' => 'SiteController' . $site->getName(),
            'site' => $site,
            'blogs' => $blog
        ]);
    }

    /**
     * @param Blog $blog
     * @Route("/blog/{blog}", name="blog_detail")
     */
    public function blogDetail(Blog $blog, $siteName = null)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByName($siteName);
        } else {
            $site = $this->siteRepository->getById(apache_getenv('SITE_ID'));
        }


        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/blog/detail.html.twig', [
            'controller_name' => 'SiteController' . $site->getName(),
            'site' => $site,
            'blog' => $blog
        ]);
    }

    /**
     * @Route("/devis", name="devis")
     */
    public function devis($siteName = null)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByName($siteName);
        } else {
            $site = $this->siteRepository->getById(apache_getenv('SITE_ID'));
        }

        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/devis/index.html.twig', [
            'controller_name' => 'SiteController' . $site->getName(),
            'site' => $site
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact($siteName = null)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByName($siteName);
        } else {
            $site = $this->siteRepository->getById(apache_getenv('SITE_ID'));
        }

        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/contact/index.html.twig', [
            'controller_name' => 'SiteController' . $site->getName(),
            'site' => $site
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home($siteName = null)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByName($siteName);
        } else {
            $site = $this->siteRepository->getById(apache_getenv('SITE_ID'));
        }

        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/index/index.html.twig', [
            'controller_name' => 'SiteController' . $site->getName(),
            'site' => $site
        ]);
    }

    /**
     * @Route("/",
     *     name="site_homepage",
     *     host="{nobackoffice}",
     *     requirements={"nobackoffice"="^((?!www.firebrick.test).)*$"},
     *     defaults={"nobackoffice"=""}
     *     )
     * @Route("/site/{siteName<.*>}/", name="site_homepage_by_id",
     *     host="www.new-cloud.test")
     * @param string $siteName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function accueil($siteName = null)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByName($siteName);
        } else {
            $site = $this->siteRepository->getById($_SERVER['ROOT']);
        }

        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/index/index.html.twig', [
            'controller_name' => 'SiteController' . $site->getName(),
            'site' => $site
        ]);
    }

}
