<?php


namespace App\Firebrock\CommandHandler;


use App\Entity\Customer;
use App\Entity\Site;
use App\Entity\User;
use App\Firebrock\Command\AddCustomerCommand;
use App\Firebrock\Command\AddSiteCommand;
use App\Repository\CustomerRepository;
use App\Repository\ProductRepository;
use App\Repository\SiteRepository;
use App\Repository\TemplateRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AddCustomerCommandHandler
 * @package App\Firebrock\CommandHandler
 */
class AddCustomerCommandHandler
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var CustomerRepository */
    private $customerRepository;

    /**
     * AddCustomerCommandHandler constructor.
     * @param SiteRepository $siteRepository
     * @param CustomerRepository $customerRepository
     */
    public function __construct(SiteRepository $siteRepository, CustomerRepository $customerRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->customerRepository = $customerRepository;
    }

    public function handle(AddCustomerCommand $command, Site $site)
    {
        $newCustomer = new Customer();
        $newCustomer->setManagerFirstName($command->getManagerFirstName());
        $newCustomer->setManagerLastName($command->getManagerLastName());
        $newCustomer->setManagerPhone($command->getManagerPhone());
        $newCustomer->setManagerMail($command->getManagerMail());

        $newCustomer = $this->customerRepository->save($newCustomer);

        // Affiliation du site au nouveau client
        $site->setCustomer($newCustomer);
        $this->siteRepository->save($site);

        return $newCustomer;
    }


}