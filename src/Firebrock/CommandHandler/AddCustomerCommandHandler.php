<?php


namespace App\Firebrock\CommandHandler;


use App\Entity\Customer;
use App\Entity\Site;
use App\Entity\User;
use App\Firebrock\Command\CustomerCommand;
use App\Firebrock\Command\OrderCommand;
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

    public function handle(CustomerCommand $command, Site $site)
    {
        $newCustomer = new Customer();
        $newCustomer->setAddress($command->getAddress());
        $newCustomer->setAddress2($command->getAddress2());
        $newCustomer->setBusinessName($command->getBusinessName());
        $newCustomer->setCity($command->getCity());
        $newCustomer->setCountry($command->getCountry());
        $newCustomer->setManagerFirstName($command->getManagerFirstName());
        $newCustomer->setManagerLastName($command->getManagerLastName());
        $newCustomer->setManagerPhone($command->getManagerPhone());
        $newCustomer->setManagerMail($command->getManagerMail());
        $newCustomer->setPostCode($command->getPostCode());

        $newCustomer = $this->customerRepository->save($newCustomer);

        // Affiliation du site au nouveau client
        $site->setCustomer($newCustomer);
        $this->siteRepository->save($site);

        return $newCustomer;
    }


}