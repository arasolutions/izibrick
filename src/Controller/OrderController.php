<?php

namespace App\Controller;

use App\Command\CustomerCommand;
use App\Command\OrderCommand;
use App\Command\SiteOptionsCommand;
use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\Site;
use App\Form\CustomerType;
use App\Form\OrderType;
use App\Form\SiteOptionsType;
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
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function index($product, Request $request)
    {
        $productChosen = $this->productRepository->findOneBy(array('id' => $product));

        $order = new OrderCommand();

        $order->setProductId($productChosen->getId());

        $form = $this->createForm(OrderType::class, $order);
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
                    $site = new Site();
                    $site->setProduct($productChosen);
                    $site->setName($order->getName());
                    $site->setColorTheme($order->getColorTheme());
                    $templateChosen = $this->templateRepository->findOneBy(array('id' => $order->getTemplate()));
                    $site->setTemplate($templateChosen);
                    if ($order->getLogo() != null) {
                        $site->setLogoFile($order->getLogo());
                    }
                    $site = $this->siteRepository->save($site);
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
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function customer($siteId, Request $request)
    {

        $site = $this->siteRepository->getById($siteId);

        $customer = new CustomerCommand();

        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newCustomer = new Customer();
            $newCustomer->setAddress($customer->getAddress());
            $newCustomer->setAddress2($customer->getAddress2());
            $newCustomer->setBusinessName($customer->getBusinessName());
            $newCustomer->setCity($customer->getCity());
            $newCustomer->setCountry($customer->getCountry());
            $newCustomer->setManagerFirstName($customer->getManagerFirstName());
            $newCustomer->setManagerLastName($customer->getManagerLastName());
            $newCustomer->setManagerPhone($customer->getManagerPhone());
            $newCustomer->setPostCode($customer->getPostCode());

            $newCustomer = $this->customerRepository->save($newCustomer);

            if ($newCustomer->getId() != null) {
                // Affiliation du site au nouveau client
                $site->setCustomer($newCustomer);
                $this->siteRepository->save($site);
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

        $form = $this->createForm(SiteOptionsType::class, $customer);
        $form->handleRequest($request);

        return $this->render('bo/order/options.html.twig', [
            'form' => $form->createView(),
            'site' => $site,
            'step' => 3
        ]);
    }
}
