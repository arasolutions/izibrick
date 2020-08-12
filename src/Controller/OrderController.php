<?php

namespace App\Controller;

use App\Entity\CodePromotion;
use App\Entity\Product;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Enum\SiteStatus;
use App\Form\EditSiteBillingType;
use App\Izibrick\Command\AddSiteCommand;
use App\Izibrick\Command\RegistrationCommand;
use App\Izibrick\Command\SiteBillingCommand;
use App\Izibrick\Command\SiteOptionsCommand;
use App\Izibrick\CommandHandler\AddInvoiceCommandHandler;
use App\Izibrick\CommandHandler\AddSiteCommandHandler;
use App\Form\AddOrderType;
use App\Form\AddSiteOptionsType;
use App\Form\OrderLoginType;
use App\Form\RegistrationType;
use App\Helper\StripeHelper;
use App\Izibrick\CommandHandler\EditSiteBillingCommandHandler;
use App\Izibrick\CommandHandler\EditSiteOptionsCommandHandler;
use App\Repository\CodePromotionRepository;
use App\Repository\InvoiceRepository;
use App\Repository\ProductRepository;
use App\Repository\SiteRepository;
use App\Repository\TemplateRepository;
use App\Repository\UserRepository;
use App\Repository\UserSiteRepository;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FormFactory;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Mailer\TwigSwiftMailer;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class OrderController extends \FOS\UserBundle\Controller\RegistrationController
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var TemplateRepository */
    private $templateRepository;

    /** @var SiteRepository */
    private $siteRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var CodePromotionRepository */
    private $codePromotionRepository;

    /** @var InvoiceRepository */
    private $invoiceRepository;

    /** @var UserManagerInterface */
    private $userManager;

    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /** @var AddInvoiceCommandHandler */
    private $addInvoiceCommandHandler;

    /** @var UserSiteRepository */
    private $userSiteRepository;

    /**
     * OrderController constructor.
     * @param ProductRepository $productRepository
     * @param TemplateRepository $templateRepository
     * @param SiteRepository $siteRepository
     * @param UserRepository $userRepository
     * @param CodePromotionRepository $codePromotionRepository
     * @param InvoiceRepository $invoiceRepository
     * @param UserManagerInterface $userManager
     * @param EncoderFactoryInterface $encoderFactory
     * @param AddInvoiceCommandHandler $addInvoiceCommandHandler
     * @param UserSiteRepository $userSiteRepository
     */
    public function __construct(ProductRepository $productRepository, TemplateRepository $templateRepository, SiteRepository $siteRepository, UserRepository $userRepository, CodePromotionRepository $codePromotionRepository, InvoiceRepository $invoiceRepository, UserManagerInterface $userManager, EncoderFactoryInterface $encoderFactory, AddInvoiceCommandHandler $addInvoiceCommandHandler, UserSiteRepository $userSiteRepository)
    {
        $this->productRepository = $productRepository;
        $this->templateRepository = $templateRepository;
        $this->siteRepository = $siteRepository;
        $this->userRepository = $userRepository;
        $this->codePromotionRepository = $codePromotionRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->userManager = $userManager;
        $this->encoderFactory = $encoderFactory;
        $this->addInvoiceCommandHandler = $addInvoiceCommandHandler;
        $this->userSiteRepository = $userSiteRepository;
    }


    /**
     * @Route("/order", name="order_index", methods={"GET"})
     * @return Response
     */
    public function orderIndex()
    {
        return $this->render('bo/order/index.html.twig', [
            'step' => 1
        ]);
    }

    /**
     * @Route("/order/product/{product}", name="order", methods={"GET","POST"}, name="order_product")
     * @param int $product
     * @param Request $request
     * @param AddSiteCommandHandler $addSiteCommandHandler
     * @return Response
     */
    public function orderProduct($product, Request $request, AddSiteCommandHandler $addSiteCommandHandler)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('order_login');
        }

        $productChosen = $this->productRepository->findOneBy(array('id' => $product));

        $order = new AddSiteCommand();

        $order->setProductId($productChosen->getId());

        $form = $this->createForm(AddOrderType::class, $order);
        $form->handleRequest($request);
        $newPrice = 0;
        $codePromo = '';
        $trialDays = '';
        $free = false;

        if ($form->isSubmitted()) {
            if ($request->get('checkCodePromotion') == '1' && $form->get('codePromo')->getData() != '') {
                //Recherche si le code promo est OK
                /** @var CodePromotion $codePromo */
                $codePromoFounded = $this->codePromotionRepository->getByName($form->get('codePromo')->getData(), $productChosen);
                if ($codePromoFounded) {
                    $codePromo = $form->get('codePromo')->getData();
                    $newPrice = floatval($codePromoFounded->getPriceDecrease());
                    $trialDays = (int)($codePromoFounded->getTrialDays());
                    if ($newPrice == 0) {
                        $free = true;
                    }
                } else {
                    $form->get('codePromo')->addError(new FormError('Code promotion inconnu'));
                }
                $form->get('name')->clearErrors();
            } else {
                if ($form->isValid()) {
                    // Nouveau site
                    $site = $addSiteCommandHandler->handle($order, $this->getUser());
                    // Stripe : On Contrôle si l'utilisateur a un comtpe Stripe si non, on le créé
                    $stripe = new StripeHelper();
                    if ($user->getStripeCustomerId() == null) {
                        $customer = $stripe->createCustomer(
                            $user->getFirstName() . ' ' . $user->getLastName(),
                            $site->getId() . '-' . $site->getName(),
                            $user->getEmail()
                        );
                        $user->setStripeCustomerId($customer);
                        $this->userRepository->save($user);
                    }
                    // Stripe : Création de l'abonnement dans Stripe
                    $trialDays = 0;
                    if ($user->getLastSite()->getSite()->getCodePromotion()) {
                        $planTarifaireId = $user->getLastSite()->getSite()->getCodePromotion()->getStripePlanTarifaireId();
                        $trialDays = $user->getLastSite()->getSite()->getCodePromotion()->getTrialDays();
                    } elseif ($user->getLastSite()->getSite()->getProduct()->getStripePlanTarifaireId()) {
                        $planTarifaireId = $user->getLastSite()->getSite()->getProduct()->getStripePlanTarifaireId();
                        $trialDays = $user->getLastSite()->getSite()->getProduct()->getTrialDays();
                    }
                    // Stripe : Abonnement du user au plan tarifaire
                    $subscription = $stripe->createSubscription($user->getStripeCustomerId(), $planTarifaireId, $trialDays);
                    $site->setStripeSubscriptionId($subscription['id']);
                    if ($subscription) {
                        return $this->redirectToRoute('dashboard');
                    }
                }
            }
        }

        return $this->render('bo/order/order.html.twig', [
            'form' => $form->createView(),
            'product' => $productChosen,
            'newPrice' => $newPrice,
            'codePromo' => $codePromo,
            'trialDays' => $trialDays,
            'free' => $free,
            'step' => 2
        ]);
    }

    /**
     * @Route("/order/{siteId}/options", name="order_options")
     * @param int $siteId
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function orderOptions($siteId, Request $request, EditSiteOptionsCommandHandler $editSiteOptionsCommandHandler)
    {

        $site = $this->siteRepository->getById($siteId);

        $options = new SiteOptionsCommand();

        $form = $this->createForm(AddSiteOptionsType::class, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $editSiteOptionsCommandHandler->handle($options, $site);

            return $this->redirect('/register?siteId=' . $siteId);
        }

        return $this->render('bo/order/options.html.twig', [
            'form' => $form->createView(),
            'site' => $site,
            'step' => 3
        ]);
    }

    /**
     * @Route("/order/{siteId}/{userId}/billing", name="order_billing")
     * @param int $siteId
     * @param $userId
     * @param Request $request
     * @param EditSiteBillingCommandHandler $billingCommandHandler
     * @return Response
     */
    public function orderBilling($siteId, $userId, Request $request, EditSiteBillingCommandHandler $billingCommandHandler)
    {
        $site = $this->siteRepository->getById($siteId);
        /** @var User $user */
        $user = $this->userRepository->get($userId);

        $billingCommand = new SiteBillingCommand();
        $billingCommand->setAddress1($user->getAddress1());
        $billingCommand->setAddress2($user->getAddress2());
        $billingCommand->setCity($user->getCity());
        $billingCommand->setPostalCode($user->getPostalCode());
        $billingCommand->setSocietyName($user->getSocietyName());

        $form = $this->createForm(EditSiteBillingType::class, $billingCommand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mis à jour du user
            $billingCommandHandler->handle($billingCommand, $userId);

            return $this->redirectToRoute('order_payment', array(
                'siteId' => $siteId,
                'userId' => $userId
            ));
        }

        return $this->render('bo/order/billing.html.twig', [
            'form' => $form->createView(),
            'site' => $site,
            'step' => 5
        ]);
    }

    /**
     * @Route("/order/{siteId}/{userId}/payment", name="order_payment")
     * @param int $siteId
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function orderPayment($siteId, $userId, Request $request)
    {
        $site = $this->siteRepository->getById($siteId);
        /** @var User $user */
        $user = $this->userRepository->get($userId);

        // On récupère le plan tarifaire associé
        $planTarifaireId = '';
        $free = false;
        $trialDays = 0;
        $stripe = new StripeHelper();
        if ($user->getLastSite()->getSite()->getCodePromotion()) {
            $planTarifaireId = $user->getLastSite()->getSite()->getCodePromotion()->getStripePlanTarifaireId();
            $trialDays = $user->getLastSite()->getSite()->getCodePromotion()->getTrialDays();
            if ($user->getLastSite()->getSite()->getCodePromotion()->getPriceDecrease() == 0) {
                $free = true;
                // Abonnement du user au plan tarifaire
                $subscription = $stripe->createSubscription($user->getStripeCustomerId(), $planTarifaireId);
                if ($subscription) {
                    // Le site devient actif
                    $site->setStatus(SiteStatus::ACTIF['name']);
                    $site->setStripeSubscriptionId($subscription['id']);
                    $this->siteRepository->save($site);
                }
            }
        } elseif ($user->getLastSite()->getSite()->getProduct()->getStripePlanTarifaireId()) {
            $planTarifaireId = $user->getLastSite()->getSite()->getProduct()->getStripePlanTarifaireId();
            $trialDays = $user->getLastSite()->getSite()->getProduct()->getTrialDays();
        }

        if ($request->isMethod('POST')) {
            $token = $request->request->get('tokenId');
            // Enregistrement de la carte dans Stripe
            $card = $stripe->createCard($user->getStripeCustomerId(), $token);
            if ($card) {
                // Abonnement du user au plan tarifaire
                $subscription = $stripe->createSubscription($user->getStripeCustomerId(), $planTarifaireId);
                $site->setStripeSubscriptionId($subscription['id']);
                if ($subscription) {
                    // Paiement accepté

                    // Le site devient actif
                    $site->setStatus(SiteStatus::ACTIF['name']);
                    $this->siteRepository->save($site);
                    return $this->render('bo/order/payment-completed.html.twig', array('newUser' => !$user->isEnabled()));
                }
                return $this->render('bo/order/payment-failure.html.twig');
            }
        }

        // On récupère les informations de la commande
        $invoiceTotalAmount = 0;
        if ($planTarifaireId != '' && $planTarifaireId != null) {
            $plan = $stripe->getPlan($planTarifaireId);
            $invoiceTotalAmount = $plan->amount / 100;
        }
        $invoiceTitle = 'Offre ' . $user->getLastSite()->getSite()->getProduct()->getName();
        $invoiceDescription = 'Abonnement du ' . date("d/m/y") . ' au ' . date('d/m/y', strtotime('+1 month'));
        $invoice = ['title' => $invoiceTitle,
            'description' => $invoiceDescription,
            'totalAmount' => $invoiceTotalAmount];//var_dump($invoiceTotalAmount);die;

        return $this->render('bo/order/payment.html.twig', [
            'site' => $site,
            'user' => $user,
            'invoice' => $invoice,
            'stripeKey' => $_ENV['STRIPE_PUBLIC_KEY'],
            'free' => $free,
            'trialDays' => $trialDays,
            'step' => 6
        ]);
    }

    /**
     * @Route("/order/login2", name="order_login2")
     * @param Request $request
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function orderLogin2(Request $request)
    {
        /** @var RegistrationCommand $command */
        $command = new RegistrationCommand($request->get('siteId'));
        $formLogin = $this->createForm(OrderLoginType::class, $command);

        $formLogin->handleRequest($request);
        $userFound = $this->userManager->findUserByEmail($command->getUsername());

        $site = $this->siteRepository->getById($command->getSiteId());

        if ($userFound != null) {
            $encoder = $this->encoderFactory->getEncoder($userFound);
            $passwordValid = $encoder->isPasswordValid($userFound->getPassword(), $command->getPlainPassword(), $userFound->getSalt());
            if (!$passwordValid) {
                // Mot de passe invalide
                $formLogin->addError(new FormError('Mauvais mot de passe'));
            } else {
                if (!$userFound->isEnabled()) {
                    $formLogin->addError(new FormError('Veuillez activer votre compte.'));
                } else {
                    /** @var User $userRepo */
                    $userRepo = $this->userRepository->get($userFound->getId());
                    $invoice = $this->addInvoiceCommandHandler->handle($userRepo, $site);

                    // Affectation du site au user
                    $userSite = new UserSite($userFound, $site);
                    $userSite = $this->userSiteRepository->save($userSite);

                    if ($invoice != null) {
                        return $this->redirectToRoute('order_billing', array('siteId' => $site->getId(), 'userId' => $userRepo->getId()));
                    }
                }
            }
        } else {
            // User non trouvé
            $formLogin->addError(new FormError('Compte inconnu'));
        }

        $formRegister = $this->createForm(RegistrationType::class, $command);

        $formRegister->handleRequest($request);

        return $this->render('@FOSUser/Registration/register.html.twig', array(
            'form' => $formRegister->createView(),
            'formLogin' => $formLogin->createView()
        ));
    }

    /**
     * @Route("/order/{siteId}/{userId}/order-valid", name="order_valid")
     * @return Response
     */
    public function orderValid($siteId, $userId, Request $request)
    {
        $site = $this->siteRepository->getById($siteId);
        /** @var User $user */
        $user = $this->userRepository->get($userId);
        return $this->render('bo/order/payment-completed.html.twig', array('newUser' => !$user->isEnabled()));
    }

    /**
     * @Route("/order/login", name="order_login")
     * @param Request $request
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function orderLogin(Request $request)
    {

        /** @var RegistrationCommand $command */
        $command = new RegistrationCommand();
        $formLogin = $this->createForm(OrderLoginType::class, $command);

        $formLogin->handleRequest($request);
        $userFound = $this->userManager->findUserByEmail($command->getUsername());

        //$site = $this->siteRepository->getById($command->getSiteId());

        if ($userFound != null) {
            $encoder = $this->encoderFactory->getEncoder($userFound);
            $passwordValid = $encoder->isPasswordValid($userFound->getPassword(), $command->getPlainPassword(), $userFound->getSalt());
            if (!$passwordValid) {
                // Mot de passe invalide
                $formLogin->addError(new FormError('Mauvais mot de passe'));
            } else {
                if (!$userFound->isEnabled()) {
                    $formLogin->addError(new FormError('Veuillez activer votre compte.'));
                } else {
                    /** @var User $userRepo */
                    $userRepo = $this->userRepository->get($userFound->getId());
                    //$invoice = $this->addInvoiceCommandHandler->handle($userRepo, $site);

                    // Affectation du site au user
                    //$userSite = new UserSite($userFound, $site);
                    //$userSite = $this->userSiteRepository->save($userSite);

                    /*if ($invoice != null) {
                        return $this->redirectToRoute('order_billing', array('siteId' => $site->getId(), 'userId' => $userRepo->getId()));
                    }*/
                    return $this->redirectToRoute('dashboard');
                }
            }
        } else {
            // User non trouvé
            $formLogin->addError(new FormError('Compte inconnu'));
        }

        $formRegister = $this->createForm(RegistrationType::class, $command);

        $formRegister->handleRequest($request);

        return $this->render('@FOSUser/Registration/register.html.twig', array(
            'form' => $formRegister->createView(),
            'formLogin' => $formLogin->createView()
        ));
    }
}
