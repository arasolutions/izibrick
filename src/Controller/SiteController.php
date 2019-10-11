<?php

namespace App\Controller;

use App\Entity\Site;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /** @var SiteRepository */
    private $siteRepository;

    /**
     * SiteController constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        /** @var Site $site */
        $site = $this->siteRepository->getById(apache_getenv('SITE_ID'));

        return $this->render('customers/customer-' . $site->getId() . '/contact/index.html.twig', [
            'controller_name' => 'SiteController' . $site->getName(),
            'site' => $site
        ]);
    }

    /**
     * @Route("/",
     *     name="site_homepage",
     *     host="{nobackoffice}",
     *     requirements={"nobackoffice"="^((?!www.new-cloud.test).)*$"},
     *     defaults={"nobackoffice"=""}
     *     )
     */
    public function accueil()
    {
        /** @var Site $site */
        $site = $this->siteRepository->getById(apache_getenv('SITE_ID'));

        return $this->render('sites/site-' . $site->getId() . '/index/index.html.twig', [
            'controller_name' => 'SiteController' . $site->getName(),
            'site' => $site
        ]);
    }

}
