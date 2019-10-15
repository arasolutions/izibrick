<?php

namespace App\Controller;

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
use App\Repository\CustomerRepository;
use App\Repository\ProductRepository;
use App\Repository\SiteRepository;
use App\Repository\TemplateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var TemplateRepository */
    private $templateRepository;

    /** @var SiteRepository */
    private $siteRepository;

    /** @var CustomerRepository */
    private $customerRepository;

    /**
     * OrderController constructor.
     * @param ProductRepository $productRepository
     * @param TemplateRepository $templateRepository
     * @param SiteRepository $siteRepository
     * @param CustomerRepository $customerRepository
     */
    public function __construct(ProductRepository $productRepository, TemplateRepository $templateRepository, SiteRepository $siteRepository, CustomerRepository $customerRepository)
    {
        $this->productRepository = $productRepository;
        $this->templateRepository = $templateRepository;
        $this->siteRepository = $siteRepository;
        $this->customerRepository = $customerRepository;
    }


    /**
     * @Route("/order/{product}", name="order", methods={"GET","POST"})
     * @param int $product
     * @param Request $request
     * @param AddSiteCommandHandler $addSiteCommandHandler
     * @return Response
     */
    public function index($product, Request $request, AddSiteCommandHandler $addSiteCommandHandler)
    {
        $productChosen = $this->productRepository->findOneBy(array('id' => $product));

        $order = new AddSiteCommand();

        $order->setProductId($productChosen->getId());

        $form = $this->createForm(AddOrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($order->getHasCodePromo() === "1") {
                //Recherche si le code promo est OK
                $newProduct = $this->productRepository->findOneBy(array('codePromotion' => $order->getCodePromo()));

                if ($newProduct != null) {
                    return $this->redirectToRoute('order', array('product' => $newProduct->getId()));
                } else {
                    $form->get('codePromo')->addError(new FormError('Code promotion inconnu'));
                }
            } else {
                if (empty($form->get('template')->getData())) {
                    $form->get('template')->addError(new FormError('Veuillez choisir un thème'));
                } else {
                    // Nouveau site
                    $site = $addSiteCommandHandler->handle($order);
                    return $this->redirectToRoute('customer', array('siteId' => $site->getId()));
                }
            }
        }

        $templates = $this->templateRepository->findBy(array('active' => 1));

        return $this->render('bo/order/index.html.twig', [
            'form' => $form->createView(),
            'product' => $productChosen,
            'templates' => $templates,
            'step' => 1
        ]);
    }

    /**
     * @Route("/order/{siteId}/customer", name="customer")
     * @param int $siteId
     * @param Request $request
     * @param AddCustomerCommandHandler $addCustomerCommandHandler
     * @return Response
     */
    public function customer($siteId, Request $request, AddCustomerCommandHandler $addCustomerCommandHandler)
    {
        $site = $this->siteRepository->getById($siteId);

        $customer = new AddCustomerCommand();

        $form = $this->createForm(AddCustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newCustomer = $addCustomerCommandHandler->handle($customer, $site);
            if ($newCustomer->getId() != null) {
                return $this->redirectToRoute('options', array('siteId' => $site->getId()));
            }
        }

        return $this->render('bo/order/customer.html.twig', [
            'form' => $form->createView(),
            'site' => $site,
            'step' => 2
        ]);
    }

    /**
     * @Route("/order/{siteId}/options", name="options")
     * @param int $siteId
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function options($siteId, Request $request)
    {

        $site = $this->siteRepository->getById($siteId);

        $customer = new SiteOptionsCommand();

        $form = $this->createForm(AddSiteOptionsType::class, $customer);
        $form->handleRequest($request);

        return $this->render('bo/order/options.html.twig', [
            'form' => $form->createView(),
            'site' => $site,
            'step' => 3
        ]);
    }

    /**
     * @Route("/order/{siteId}/end", name="end")
     * @param $siteId
     * @param Request $request
     */
    public function end($siteId, Request $request){
        $site = $this->siteRepository->getById($siteId);

    }
}
