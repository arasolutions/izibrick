<?php


namespace App\Firebrock\CommandHandler;

use App\Entity\Invoice;
use App\Entity\Site;
use App\Entity\TrackingQuote;
use App\Entity\User;
use App\Firebrock\Command\AddTrackingQuoteCommand;
use App\Helper\StripeHelper;
use App\Repository\InvoiceRepository;
use App\Repository\TrackingQuoteRepository;
use App\Repository\UserRepository;

/**
 * Class AddBlogCommandHandler
 * @package App\Firebrock\CommandHandler
 */
class AddInvoiceCommandHandler
{
    /** @var InvoiceRepository $invoiceRepository */
    private $invoiceRepository;

    /** @var UserRepository $userRepository */
    private $userRepository;

    /**
     * AddInvoiceCommandHandler constructor.
     * @param InvoiceRepository $invoiceRepository
     * @param UserRepository $userRepository
     */
    public function __construct(InvoiceRepository $invoiceRepository, UserRepository $userRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->userRepository = $userRepository;
    }


    /**
     * @param User $user
     * @param Site $site
     * @return int|null
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function handle(User $user, Site $site)
    {
        $stripe = new StripeHelper();
        if ($user->getStripeCustomerId() == null) {
            $customer = $stripe->createCustomer($user->getId() . '-' . $user->getEmail(), $site->getProduct()->getName(), $user->getEmail());
            $user->setStripeCustomerId($customer);
            $this->userRepository->save($user);
        }

        // Enregistrement de la facture
        $invoice = new Invoice($site);
        $invoice->setFirstName($user->getFirstName());
        $invoice->setLastName($user->getLastname());
        $invoice->setInvoiceNumber('F-' . $user->getId() . '-' . time());
        $invoice->setTitle('Offre ' . $site->getProduct()->getName());
        $invoice->setDescription('Abonnement du ' . date("d/m/y") . ' au ' . date('d/m/y', strtotime('+1 month')));
        $invoice->setQuantity('1');
        $invoice->setUnitPrice($site->getProduct()->getPrice());
        $invoice->setPrice($site->getProduct()->getPrice());
        $invoice->setVatRate('20');
        $invoice->setTotalAmount(($site->getProduct()->getPrice()) + ($site->getProduct()->getPrice() * 20 / 100));
        $this->invoiceRepository->save($invoice);

        return $invoice->getId();
    }
}