<?php

namespace App\Controller\Admin;

use App\Entity\Invoice;
use App\Enum\Constants;
use App\Helper\StripeHelper;
use App\Izibrick\Command\AddInvoiceCommand;
use App\Izibrick\CommandHandler\AddInvoiceCommandHandler;
use App\Form\AddInvoiceType;
use App\Repository\SiteRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class InvoiceController
 * @Route("/admin/bo-invoice")
 * @package App\Controller\Admin
 */
class InvoiceController extends AbstractController
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var UserRepository */
    private $userRepository;

    /**
     * InvoiceController constructor.
     * @param SiteRepository $siteRepository
     * @param UserRepository $userRepository
     */
    public function __construct(SiteRepository $siteRepository, UserRepository $userRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="bo-invoice")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boInvoice(Request $request, $success = false)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        $user = $this->userRepository->get($this->getUser()->getId());

        $stripe = new StripeHelper();
        // Récupération des factures pour le user
        $invoices = $stripe->getListInvoices($user->getStripeCustomerId());

        return $this->render('admin/invoice/index.html.twig', [
            'controller_name' => 'InvoiceController',
            'site' => $site,
            'invoices' => $invoices
        ]);
    }

}
