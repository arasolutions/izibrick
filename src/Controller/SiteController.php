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
use App\Repository\PricingRepository;
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
    /** @var PricingRepository */
    private $pricingRepository;

    /**
     * SiteController constructor.
     * @param SiteRepository $siteRepository
     * @param BlogRepository $blogRepository
     * @param PricingRepository $pricingRepository
     */
    public function __construct(SiteRepository $siteRepository, BlogRepository $blogRepository, PricingRepository $pricingRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->blogRepository = $blogRepository;
        $this->pricingRepository = $pricingRepository;
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
            $site = $this->siteRepository->getByInternalName($siteName);
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
            $site = $this->siteRepository->getByInternalName($siteName);
        } else {
            $site = $this->siteRepository->getByDomain($_SERVER['HTTP_HOST']);
        }

        $blog = $this->blogRepository->getBySiteId($site->getId());

        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/blog/index.html.twig', [
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
            $site = $this->siteRepository->getByInternalName($siteName);
        } else {
            $site = $this->siteRepository->getByDomain($_SERVER['HTTP_HOST']);
        }


        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/blog/detail.html.twig', [
            'site' => $site,
            'post' => $post
        ]);
    }

    /**
     * @Route("/tarif",
     *     name="tarif",
     *     host="{nobackoffice}",
     *     requirements={"nobackoffice"="^((?!%base_host%).)*$"},
     *     defaults={"nobackoffice"=""}
     *     )
     * @Route("/site/{siteName<.*>}/tarif", name="site_tarif",
     *     host="%base_host%")
     */
    public function pricing($siteName = null)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByInternalName($siteName);
        } else {
            $site = $this->siteRepository->getByDomain($_SERVER['HTTP_HOST']);
        }

        $pricing = $this->pricingRepository->getBySiteId($site->getId());

        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/pricing/index.html.twig', [
            'controller_name' => 'SiteController' . $site->getName(),
            'site' => $site,
            'pricing' => $pricing,
            'categories' => $site->getPricingCategories(),
            'products' => $site->getPricingProducts()
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
            $site = $this->siteRepository->getByInternalName($siteName);
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
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function contact(Request $request, $siteName,
                            AddTrackingContactCommandHandler $addTrackingContactCommandHandler, \Swift_Mailer $mailer)
    {
        /** @var Site $site */
        if ($siteName != null) {
            $site = $this->siteRepository->getByInternalName($siteName);
        } else {
            $site = $this->siteRepository->getByDomain($_SERVER['HTTP_HOST']);
        }
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
            $site = $this->siteRepository->getByInternalName($siteName);
        } else {
            $site = $this->siteRepository->getByDomain($_SERVER['HTTP_HOST']);
        }

        return $this->render('sites/template-' . $site->getTemplate()->getId() . '/index/index.html.twig', [
            'site' => $site
        ]);
    }

}
