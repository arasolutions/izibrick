<?php

namespace App\Controller\Admin;

use App\Entity\Pricing;
use App\Entity\PricingCategory;
use App\Entity\PricingProduct;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Entity\Post;
use App\Enum\Constants;
use App\Helper\SiteHelper;
use App\Form\EditPricingCategoryType;
use App\Form\EditPricingProductType;
use App\Izibrick\Command\ContactCommand;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Izibrick\Command\PricingCategoryCommand;
use App\Izibrick\Command\PricingCommand;
use App\Izibrick\Command\PostCommand;
use App\Izibrick\Command\PricingProductCommand;
use App\Izibrick\Command\RemovePricingCategoryCommand;
use App\Izibrick\Command\RemovePricingCommand;
use App\Izibrick\Command\HomeCommand;
use App\Izibrick\Command\PresentationCommand;
use App\Izibrick\Command\RemovePricingProductCommand;
use App\Izibrick\CommandHandler\EditContactCommandHandler;
use App\Izibrick\CommandHandler\EditGlobalParametersCommandHandler;
use App\Izibrick\CommandHandler\EditPricingCategoryCommandHandler;
use App\Izibrick\CommandHandler\EditPricingCommandHandler;
use App\Izibrick\CommandHandler\EditPostCommandHandler;
use App\Izibrick\CommandHandler\EditPricingProductCommandHandler;
use App\Izibrick\CommandHandler\RemovePricingCategoryCommandHandler;
use App\Izibrick\CommandHandler\RemovePricingCommandHandler;
use App\Izibrick\CommandHandler\EditHomeCommandHandler;
use App\Izibrick\CommandHandler\EditPresentationCommandHandler;
use App\Form\EditContactType;
use App\Form\EditGlobalParametersType;
use App\Form\EditPricingType;
use App\Form\EditHomeType;
use App\Form\EditPostType;
use App\Form\EditPresentationType;
use App\Form\AddSiteOptionsType;
use App\Izibrick\CommandHandler\RemovePricingProductCommandHandler;
use App\Repository\PricingCategoryRepository;
use App\Repository\PricingProductRepository;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PricingController
 * @Route("/admin/bo-pricing")
 * @package App\Controller\Admin
 */
class PricingController extends AbstractController
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var PricingCategoryRepository */
    private $pricingCategoryRepository;

    /** @var PricingProductRepository */
    private $pricingProductRepository;

    /**
     * PricingController constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(SiteRepository $siteRepository,
                                PricingCategoryRepository $pricingCategoryRepository,
                                PricingProductRepository $pricingProductRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->pricingCategoryRepository = $pricingCategoryRepository;
        $this->pricingProductRepository = $pricingProductRepository;
    }

    /**
     * @Route("/", name="bo-pricing")
     * @param Request $request
     * @param EditPricingCommandHandler $editPricingCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boPricing(Request $request, EditPricingCommandHandler $editPricingCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        $categories = $this->pricingCategoryRepository->getAllBySiteId($_SESSION[Constants::SESSION_SITE_ID]);
        $products = $this->pricingProductRepository->getAllBySiteId($_SESSION[Constants::SESSION_SITE_ID]);
        $success = false;

        $command = new PricingCommand();

        $form = $this->createForm(EditPricingType::class, $command, ['idSite' => SiteHelper::getuniqueKeySite($site)]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editPricingCommandHandler->handle($command, $site);
            $success = true;
        }

        return $this->render('admin/pricing/index.html.twig', [
            'site' => $site,
            'form' => $form->createView(),
            'success' => $success,
            'categories' => $categories,
            'products' => $products
        ]);
    }

    /**
     * @Route("/product/add", name="bo-pricing-product-add")
     * @param Request $request
     * @param EditPricingProductCommandHandler $editPricingProductCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boAddPricing(Request $request, EditPricingProductCommandHandler $editPricingProductCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);

        $command = new PricingProductCommand();

        $success = false;

        $form = $this->createForm(EditPricingProductType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $editPricingProductCommandHandler->handle($command, $site);
            $success = true;
            return $this->redirectToRoute('bo-pricing', [
                'success' => $success
            ]);
        }

        return $this->render('admin/pricing/add.html.twig', [
            'site' => $site,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/product/{id}/edit", name="bo-pricing-product-edit")
     * @param Post $post
     * @param Request $request
     * @param EditPricingProductCommandHandler $editPricingProductCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boEditPricing(PricingProduct $pricingProduct, Request $request, EditPricingProductCommandHandler $editPricingProductCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);

        $command = new PricingProductCommand();
        $command->id = $pricingProduct->getId();
        $command->name = $pricingProduct->getName();
        $command->content = $pricingProduct->getContent();
        $command->category = $pricingProduct->getCategory();
        $command->price = $pricingProduct->getPrice();
        $command->active = $pricingProduct->getActive();
        $command->currency = $pricingProduct->getCurrency();

        $success = false;

        $form = $this->createForm(EditPricingProductType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $editPricingProductCommandHandler->handle($command, $site);
            $success = true;
            return $this->redirectToRoute('bo-pricing', [
                'success' => $success
            ]);
        }

        return $this->render('admin/pricing/add.html.twig', [
            'site' => $site,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @param PricingProduct $pricingProduct
     * @param RemovePricingProductCommandHandler $handler
     * @return Response
     *
     * @Route("/product/{id}/remove", name="bo-pricing-product-remove")
     * @method ({
    "GET"
    })
     */
    public function removePricing(PricingProduct $pricingProduct, RemovePricingProductCommandHandler $handler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        $command = new RemovePricingProductCommand();
        $command->id = $pricingProduct->getId();

        try {
            $handler->handle($command);
            $success = true;
        } catch (\Exception $e) {
            $success = false;
        }

        return $this->render('admin/pricing/index.html.twig', [
            'site' => $site,
            'categories' => $site->getPricingCategories(),
            'products' => $site->getPricingProducts(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/category", name="bo-pricing-category")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boCategoryPricing(Request $request)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        $categories = $this->pricingCategoryRepository->getAllBySiteId($_SESSION[Constants::SESSION_SITE_ID]);
        $success = false;

        return $this->render('admin/pricing/category/index.html.twig', [
            'site' => $site,
            'success' => $success,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/category/add", name="bo-pricing-category-add")
     * @param Request $request
     * @param EditPricingCategoryCommandHandler $editPricingCategoryCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boAddCategoryPricing(Request $request, EditPricingCategoryCommandHandler $editPricingCategoryCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);

        $command = new PricingCategoryCommand();

        $success = false;

        $form = $this->createForm(EditPricingCategoryType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editPricingCategoryCommandHandler->handle($command, $site);
            $success = true;
            return $this->render('admin/pricing/category/index.html.twig', [
                'site' => $site,
                'categories' => $site->getPricingCategories(),
                'success' => $success
            ]);
        }

        return $this->render('admin/pricing/category/add.html.twig', [
            'site' => $site,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/category/{id}/edit", name="bo-pricing-category-edit")
     * @param Post $post
     * @param Request $request
     * @param EditPricingCategoryCommandHandler $editPricingCategoryCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boEditCategoryPricing(PricingCategory $pricingCategory, Request $request, EditPricingCategoryCommandHandler $editPricingCategoryCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);

        $command = new PricingCategoryCommand();
        $command->id = $pricingCategory->getId();
        $command->name = $pricingCategory->getName();
        $command->active = $pricingCategory->getActive();

        $success = false;

        $form = $this->createForm(EditPricingCategoryType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $editPricingCategoryCommandHandler->handle($command, $site);
            $success = true;
            return $this->render('admin/pricing/category/index.html.twig', [
                'site' => $site,
                'categories' => $site->getPricingCategories(),
                'success' => $success
            ]);
        }

        return $this->render('admin/pricing/category/add.html.twig', [
            'site' => $site,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @param PricingCategory $pricingCategory
     * @param RemovePricingCategoryCommandHandler $handler
     * @return Response
     *
     * @Route("/category/{id}/remove", name="bo-pricing-category-remove")
     * @method ({
    "GET"
    })
     */
    public function removeCategoryPricing(PricingCategory $pricingCategory, RemovePricingCategoryCommandHandler $handler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        $command = new RemovePricingCategoryCommand();
        $command->id = $pricingCategory->getId();

        try {
            $handler->handle($command);
            $success = true;
        } catch (\Exception $e) {
            $success = false;
        }

        return $this->render('admin/pricing/category/index.html.twig', [
            'site' => $site,
            'categories' => $site->getPricingCategories(),
            'success' => $success
        ]);
    }
}
