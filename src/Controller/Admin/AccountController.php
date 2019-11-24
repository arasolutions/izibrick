<?php

namespace App\Controller\Admin;

use App\Entity\Account;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Form\EditSiteBillingType;
use App\Helper\StripeHelper;
use App\Izibrick\Command\ContactCommand;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Izibrick\Command\PostCommand;
use App\Izibrick\Command\HomeCommand;
use App\Izibrick\Command\PresentationCommand;
use App\Izibrick\Command\SiteBillingCommand;
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
use App\Izibrick\CommandHandler\EditSiteBillingCommandHandler;
use App\Repository\SiteRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AccountController
 * @Route("/admin/bo-account")
 * @package App\Controller\Admin
 */
class AccountController extends AbstractController
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var UserRepository */
    private $userRepository;

    /**
     * AccountController constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(SiteRepository $siteRepository, UserRepository $userRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/{success}", name="bo-account")
     * @param Request $request
     * @param bool $success
     * @param EditSiteBillingCommandHandler $editSiteBillingCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function boAccount(Request $request, $success = false, EditSiteBillingCommandHandler $editSiteBillingCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);
        /** @var User $user */
        $user = $this->getUser();
        $userBDD = $this->userRepository->get($this->getUser()->getId());

        $command = new SiteBillingCommand();
        $command->setCity($user->getCity());
        $command->setPostalCode($user->getPostalCode());
        $command->setSocietyName($user->getSocietyName());
        $command->setAddress1($user->getAddress1());
        $command->setAddress2($user->getAddress2());

        $form = $this->createForm(EditSiteBillingType::class, $command);

        $form->handleRequest($request);

        // Afficher les moyens de paiement
        $stripe = new StripeHelper();
        $customer = $stripe->getCustomer($userBDD->getStripeCustomerId());
        $cards = $stripe->getAllCards($userBDD->getStripeCustomerId());

        if ($form->isSubmitted() && $form->isValid()) {
            // Mis à jour du user
            $editSiteBillingCommandHandler->handle($command, $user->getId());

            return $this->render('admin/account/index.html.twig', [
                'site' => $site,
                'user' => $user,
                'success' => true,
                'form' => $form->createView(),
                'customer' => $customer,
                'cards' => $cards
            ]);
        }

        return $this->render('admin/account/index.html.twig', [
            'site' => $site,
            'user' => $user,
            'success' => $success,
            'form' => $form->createView(),
            'customer' => $customer,
            'cards' => $cards
        ]);
    }

    /**
     * @Route("/card/add", name="bo-account-card-add")
     * @param Request $request
     * @param bool $success
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function boCardAddAccount(Request $request, $success = false)
    {
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);
        /** @var User $user */
        $user = $this->getUser();
        $userBDD = $this->userRepository->get($this->getUser()->getId());

        // Afficher le moyen de paiement
        $stripe = new StripeHelper();
        $customer = $stripe->getCustomer($userBDD->getStripeCustomerId());//var_dump($customer);die;

        if ($request->isMethod('POST')) {
            $token = $request->request->get('tokenId');
            // Enregistrement de la carte dans Stripe
            $card = $stripe->createCard($user->getStripeCustomerId(), $token);
            if($card){
                return $this->redirectToRoute('bo-account', array('success' => true));
            }
        }

        return $this->render('admin/account/card-add.html.twig', [
            'site' => $site,
            'user' => $user,
            'success' => $success,
            'customer' => $customer,
            'stripeKey' => $_ENV['STRIPE_PUBLIC_KEY'],
        ]);
    }

    /**
     * @Route("/card/delete/{id}", name="bo-account-card-delete")
     * @param Request $request
     * @param bool $success
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function boCardDeleteAccount(Request $request, $id = null, $success = false)
    {
        /** @var User $user */
        $user = $this->getUser();
        $userBDD = $this->userRepository->get($this->getUser()->getId());

        // Afficher le moyen de paiement
        $stripe = new StripeHelper();
        $customer = $stripe->getCustomer($userBDD->getStripeCustomerId());//var_dump($customer);die;

        if ($request->isMethod('GET')) {
            $cardId = $request->get('id');
            // Suppression de la carte dans Stripe
            $card = $stripe->deleteCard($user->getStripeCustomerId(), $cardId);
            if($card){
                return $this->redirectToRoute('bo-account', array('success' => true));
            }
        }

        return $this->redirectToRoute('bo-account');
    }

    /**
     * @Route("/card/default/{id}", name="bo-account-card-default")
     * @param Request $request
     * @param bool $success
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function boCardDefaultAccount(Request $request, $id = null, $success = false)
    {
        /** @var User $user */
        $user = $this->getUser();
        $userBDD = $this->userRepository->get($this->getUser()->getId());

        // Afficher le moyen de paiement
        $stripe = new StripeHelper();
        $customer = $stripe->getCustomer($userBDD->getStripeCustomerId());//var_dump($customer);die;

        if ($request->isMethod('GET')) {
            $cardId = $request->get('id');
            // Suppression de la carte dans Stripe
            $customer = $stripe->updateCustomerDefaultSource($user->getStripeCustomerId(), $cardId);
            if($customer){
                return $this->redirectToRoute('bo-account', array('success' => true));
            }
        }

        return $this->redirectToRoute('bo-account');
    }
}
