<?php

namespace App\Controller;

use App\Command\GlobalParametersCommand;
use App\Command\HomeCommand;
use App\Entity\Site;
use App\Entity\User;
use App\Form\GlobalParametersType;
use App\Form\HomeType;
use App\Form\SiteOptionsType;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
            if (sizeof($user->getSites()) == 1) {
                /** @var Site $site */
                $site = $user->getSites()[0];
                $_SESSION['SITE_ID'] = $site->getId();
            }
        }

        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

        return $this->render('admin/dashboard/index.html.twig', [
            'controller_name' => 'AdminController',
            'site' => $site
        ]);
    }

    /**
     * @Route("/accueil", name="bo-accueil")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function accueil(Request $request)
    {
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

        $command = new HomeCommand();
        $command->setOriginalContent($site->getHome()->getContent());

        $success = false;

        $form = $this->createForm(HomeType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $site->getHome()->setContent($command->getContent());
            $this->siteRepository->save($site);
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
     * @Route("/global-parameters", name="bo-global-parameters")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function globalParams(Request $request)
    {
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

        $command = new GlobalParametersCommand($site);

        $success = false;

        $form = $this->createForm(GlobalParametersType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $site->setKeyWords($command->getKeys());
            $site->setFacebook($command->getFacebook());
            $site->setTwitter($command->getTwitter());
            $site->setInstagram($command->getInstagram());
            $this->siteRepository->save($site);
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
