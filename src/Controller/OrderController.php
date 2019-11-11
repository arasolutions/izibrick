<?php

namespace App\Controller;

use App\Entity\CodePromotion;
use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\Site;
use App\Entity\User;
use App\Izibrick\Command\AddCustomerCommand;
use App\Izibrick\Command\AddSiteCommand;
use App\Izibrick\Command\RegistrationCommand;
use App\Izibrick\Command\SiteOptionsCommand;
use App\Izibrick\CommandHandler\AddCustomerCommandHandler;
use App\Izibrick\CommandHandler\AddInvoiceCommandHandler;
use App\Izibrick\CommandHandler\AddSiteCommandHandler;
use App\Form\AddCustomerType;
use App\Form\AddOrderType;
use App\Form\AddSiteOptionsType;
use App\Form\OrderLoginType;
use App\Form\RegistrationType;
use App\Helper\StripeHelper;
use App\Repository\CodePromotionRepository;
use App\Repository\CustomerRepository;
use App\Repository\InvoiceRepository;
use App\Repository\ProductRepository;
use App\Repository\SiteRepository;
use App\Repository\TemplateRepository;
use App\Repository\UserRepository;
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

    /** @var CustomerRepository */
    private $customerRepository;

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

    /**
     * OrderController constructor.
     * @param ProductRepository $productRepository
     * @param TemplateRepository $templateRepository
     * @param SiteRepository $siteRepository
     * @param CustomerRepository $customerRepository
     * @param UserRepository $userRepository
     * @param CodePromotionRepository $codePromotionRepository
     * @param InvoiceRepository $invoiceRepository
     * @param UserManagerInterface $userManager
     * @param EncoderFactoryInterface $encoderFactory
     * @param AddInvoiceCommandHandler $addInvoiceCommandHandler
     */
    public function __construct(ProductRepository $productRepository, TemplateRepository $templateRepository, SiteRepository $siteRepository, CustomerRepository $customerRepository, UserRepository $userRepository, CodePromotionRepository $codePromotionRepository, InvoiceRepository $invoiceRepository, UserManagerInterface $userManager, EncoderFactoryInterface $encoderFactory, AddInvoiceCommandHandler $addInvoiceCommandHandler)
    {
        $this->productRepository = $productRepository;
        $this->templateRepository = $templateRepository;
        $this->siteRepository = $siteRepository;
        $this->customerRepository = $customerRepository;
        $this->userRepository = $userRepository;
        $this->codePromotionRepository = $codePromotionRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->userManager = $userManager;
        $this->encoderFactory = $encoderFactory;
        $this->addInvoiceCommandHandler = $addInvoiceCommandHandler;
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
        $productChosen = $this->productRepository->findOneBy(array('id' => $product));

        $order = new AddSiteCommand();

        $order->setProductId($productChosen->getId());

        $form = $this->createForm(AddOrderType::class, $order);
        $form->handleRequest($request);
        $newPrice = 0;
        $codePromo = '';
        $free = false;

        if ($form->isSubmitted()) {
            if ($request->get('checkCodePromotion') == '1' && $form->get('codePromo')->getData() != '') {
                //Recherche si le code promo est OK
                /** @var CodePromotion $codePromo */
                $codePromoFounded = $this->codePromotionRepository->getByName($form->get('codePromo')->getData(), $productChosen);
                if ($codePromoFounded) {
                    $codePromo = $form->get('codePromo')->getData();
                    $newPrice = floatval($codePromoFounded->getPriceDecrease());
                    if($newPrice == 0){
                        $free = true;
                    }
                } else {
                    $form->get('codePromo')->addError(new FormError('Code promotion inconnu'));
                }
            } else {
                if ($form->isValid()) {
                    if (empty($form->get('template')->getData())) {
                        $form->get('template')->addError(new FormError('Veuillez choisir un thème'));
                    } else {
                        // Nouveau site
                        $site = $addSiteCommandHandler->handle($order);
                        return $this->redirectToRoute('order_options', array('siteId' => $site->getId()));
                    }
                }
            }
        }

        $templates = $this->templateRepository->findBy(array('active' => 1));

        return $this->render('bo/order/order.html.twig', [
            'form' => $form->createView(),
            'product' => $productChosen,
            'templates' => $templates,
            'newPrice' => $newPrice,
            'codePromo' => $codePromo,
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
    public function orderOptions($siteId, Request $request)
    {

        $site = $this->siteRepository->getById($siteId);

        $customer = new SiteOptionsCommand();

        $form = $this->createForm(AddSiteOptionsType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirect('/register?siteId=' . $siteId);
        }

        return $this->render('bo/order/options.html.twig', [
            'form' => $form->createView(),
            'site' => $site,
            'step' => 3
        ]);
    }

    /**
     * @Route("/order/{siteId}/end", name="order_end")
     * @param $siteId
     * @param Request $request
     */
    public function orderEnd($siteId, Request $request)
    {
        $site = $this->siteRepository->getById($siteId);

    }

    /**
     * @Route("/order/{siteId}/{userId}/payment", name="order_payment")
     * @param int $siteId
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function orderPayment($siteId, $userId, Request $request)
    {
        $site = $this->siteRepository->getById($siteId);
        $user = $this->userRepository->get($userId);

        // On récupère le plan tarifaire associé
        $planTarifaireId = '';
        $free = false;
        if($user->getLastSite()->getSite()->getCodePromotion()){
            $planTarifaireId = $user->getLastSite()->getSite()->getCodePromotion()->getStripePlanTarifaireId();
            if($user->getLastSite()->getSite()->getCodePromotion()->getPriceDecrease() == 0){
                $free = true;
            }
        }elseif ($user->getLastSite()->getSite()->getProduct()->getStripePlanTarifaireId()){
            $planTarifaireId = $user->getLastSite()->getSite()->getProduct()->getStripePlanTarifaireId();
        }
        $stripe = new StripeHelper();

        if ($request->isMethod('POST')) {
            $token = $request->request->get('tokenId');
            // Enregistrement de la carte dans Stripe
            $card = $stripe->createCard($user->getStripeCustomerId(), $token);
            if($card){
                // Abonnement du user au plan tarifaire
                $subscription = $stripe->createSubscription($user->getStripeCustomerId(), $planTarifaireId);
                if ($subscription) {
                    // Paiement accepté
                    return $this->render('bo/order/payment-completed.html.twig');
                }
                return $this->render('bo/order/payment-failure.html.twig');
            }
        }

        // On récupère les informations de la commande
        $invoiceTotalAmount = '0';
        if($planTarifaireId != '' && $planTarifaireId != null){
            $plan = $stripe->getPlan($planTarifaireId);
            $invoiceTotalAmount= ($plan->amount/100) + ($plan->amount/100*20/100);
        }
        $invoiceTitle= 'Offre '.$user->getLastSite()->getSite()->getProduct()->getName();
        $invoiceDescription= 'Abonnement du ' . date("d/m/y") . ' au ' . date('d/m/y', strtotime('+1 month'));
        $invoice = ['title' => $invoiceTitle,
            'description' => $invoiceDescription,
            'totalAmount' => $invoiceTotalAmount];

        return $this->render('bo/order/payment.html.twig', [
            'site' => $site,
            'user' => $user,
            'invoice' => $invoice,
            'stripeKey' => $_ENV['STRIPE_PUBLIC_KEY'],
            'free' => $free,
            'step' => 5
        ]);
    }

    /**
     * @Route("/order/login", name="order_login")
     * @param Request $request
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function orderLogin(Request $request)
    {
        /** @var RegistrationCommand $command */
        $command = new RegistrationCommand($request->get('siteId'));
        $formLogin = $this->createForm(OrderLoginType::class, $command);

        $formLogin->handleRequest($request);
        $userFound = $this->userManager->findUserByEmail($command->getUsername());

        $site = $this->siteRepository->getById($command->getSiteId());

        if($userFound!= null){
            $encoder = $this->encoderFactory->getEncoder($userFound);
            $passwordValid = $encoder->isPasswordValid($userFound->getPassword(), $command->getPlainPassword(), $userFound->getSalt());
            if(!$passwordValid){
                // Mot de passe invalide
                $formLogin->addError(new FormError('Mauvais mot de passe'));
            }else{
                if(!$userFound->isEnabled()){
                    $formLogin->addError(new FormError('Veuillez activer votre compte.'));
                }else{
                    /** @var User $userRepo */
                    $userRepo = $this->userRepository->get($userFound->getId());
                    $invoice = $this->addInvoiceCommandHandler->handle($userRepo, $site);
                    if ($invoice != null) {
                        return $this->redirectToRoute('order_payment', array('siteId' => $site->getId(), 'userId' => $userRepo->getId()));
                    }
                }
            }
        }else{
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
     * @Route("/order-valid", name="order_valid")
     * @return Response
     */
    public function orderValid()
    {
        return $this->render('bo/order/payment-completed.html.twig');
    }
}
