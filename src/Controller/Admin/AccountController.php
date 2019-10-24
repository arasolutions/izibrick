<?php

namespace App\Controller\Admin;

use App\Entity\Account;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Firebrock\Command\ContactCommand;
use App\Firebrock\Command\GlobalParametersCommand;
use App\Firebrock\Command\PostCommand;
use App\Firebrock\Command\HomeCommand;
use App\Firebrock\Command\PresentationCommand;
use App\Firebrock\CommandHandler\EditContactCommandHandler;
use App\Firebrock\CommandHandler\EditGlobalParametersCommandHandler;
use App\Firebrock\CommandHandler\EditPostCommandHandler;
use App\Firebrock\CommandHandler\EditHomeCommandHandler;
use App\Firebrock\CommandHandler\EditPresentationCommandHandler;
use App\Form\EditContactType;
use App\Form\EditGlobalParametersType;
use App\Form\EditHomeType;
use App\Form\EditPostType;
use App\Form\EditPresentationType;
use App\Form\AddSiteOptionsType;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AccountController
 * @Route("/bo-account")
 * @package App\Controller\Admin
 */
class AccountController extends AbstractController
{
    /** @var SiteRepository */
    private $siteRepository;

    /**
     * AccountController constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    /**
     * @Route("/", name="bo-account")
     * @param Request $request
     * @param EditPresentationCommandHandler $editPresentationCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boAccount(Request $request, $success = false)
    {
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

        return $this->render('admin/account/index.html.twig', [
            'controller_name' => 'AccountController',
            'site' => $site,
            'success' => $success
        ]);
    }

}
