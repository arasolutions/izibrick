<?php

namespace App\Controller\Admin;

use App\Entity\Account;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Izibrick\Command\ContactCommand;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Izibrick\Command\PostCommand;
use App\Izibrick\Command\HomeCommand;
use App\Izibrick\Command\PresentationCommand;
use App\Izibrick\CommandHandler\EditContactCommandHandler;
use App\Izibrick\CommandHandler\EditGlobalParametersCommandHandler;
use App\Izibrick\CommandHandler\EditPostCommandHandler;
use App\Izibrick\CommandHandler\EditHomeCommandHandler;
use App\Izibrick\CommandHandler\EditPresentationCommandHandler;
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
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('admin/account/index.html.twig', [
            'controller_name' => 'AccountController',
            'site' => $site,
            'user' => $user,
            'success' => $success
        ]);
    }

}
