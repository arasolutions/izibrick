<?php

namespace App\Controller;

use App\Entity\CodePromotion;
use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\Site;
use App\Firebrock\Command\AddCustomerCommand;
use App\Firebrock\Command\AddSiteCommand;
use App\Firebrock\Command\SiteOptionsCommand;
use App\Firebrock\CommandHandler\AddCustomerCommandHandler;
use App\Firebrock\CommandHandler\AddSiteCommandHandler;
use App\Form\AddCustomerType;
use App\Form\AddOrderType;
use App\Form\AddSiteOptionsType;
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

    /**
     * OrderController constructor.
     * @param ProductRepository $productRepository
     * @param TemplateRepository $templateRepository
     * @param SiteRepository $siteRepository
     * @param CustomerRepository $customerRepository
     * @param UserRepository $userRepository
     * @param CodePromotionRepository $codePromotionRepository
     * @param InvoiceRepository $invoiceRepository
     */
    public function __construct(ProductRepository $productRepository, TemplateRepository $templateRepository, SiteRepository $siteRepository, CustomerRepository $customerRepository, UserRepository $userRepository, CodePromotionRepository $codePromotionRepository, InvoiceRepository $invoiceRepository)
    {
        $this->productRepository = $productRepository;
        $this->templateRepository = $templateRepository;
        $this->siteRepository = $siteRepository;
        $this->customerRepository = $customerRepository;
        $this->userRepository = $userRepository;
        $this->codePromotionRepository = $codePromotionRepository;
        $this->invoiceRepository = $invoiceRepository;
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
     * @Route("/order/{product}", name="order", methods={"GET","POST"}, name="order_product")
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

        if ($form->isSubmitted()) {
            if ($request->get('checkCodePromotion') == '1' && $form->get('codePromo')->getData() != '') {
                //Recherche si le code promo est OK
                /** @var CodePromotion $codePromo */
                $codePromoFounded = $this->codePromotionRepository->getByName($form->get('codePromo')->getData(), $productChosen);
                if($codePromoFounded){
                    $codePromo = $form->get('codePromo')->getData();
                    $newPrice = floatval($productChosen->getPrice()) + floatval($codePromoFounded->getPriceDecrease());
                }else {
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
        $invoice = $this->invoiceRepository->getBySiteId($siteId);

        if ($request->isMethod('POST')) {
            $token = $request->request->get('tokenId');
            $stripe = new StripeHelper();
            // Enregistrement de la carte dans Stripe
            //$card = $stripe->createCard($user->getStripeCustomerId(), $token);
            //var_dump($card);die;
            // On exécute le paiement
            $charge = $stripe->createCharge($invoice->getTotalAmount(), $token, $invoice->getTitle().' - '.$invoice->getDescription());
            if($charge){
                // Paiement accepté
                return $this->render('bo/order/payment-completed.html.twig');
            }
            return $this->render('bo/order/payment-failure.html.twig');
        }

        return $this->render('bo/order/payment.html.twig', [
            'site' => $site,
            'user' => $user,
            'invoice' => $invoice,
            'step' => 5
        ]);
    }
}
