<?php


namespace App\Izibrick\CommandHandler;


use App\Entity\Customer;
use App\Entity\Site;
use App\Entity\User;
use App\Izibrick\Command\AddCustomerCommand;
use App\Izibrick\Command\AddSiteCommand;
use App\Helper\UserHelper;
use App\Repository\CustomerRepository;
use App\Repository\ProductRepository;
use App\Repository\SiteRepository;
use App\Repository\TemplateRepository;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AddCustomerCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class AddCustomerCommandHandler
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var CustomerRepository */
    private $customerRepository;

    /** @var UserManagerInterface */
    private $userManager;

    /**
     * AddCustomerCommandHandler constructor.
     * @param SiteRepository $siteRepository
     * @param CustomerRepository $customerRepository
     * @param UserManagerInterface $userManager
     */
    public function __construct(SiteRepository $siteRepository, CustomerRepository $customerRepository, UserManagerInterface $userManager)
    {
        $this->siteRepository = $siteRepository;
        $this->customerRepository = $customerRepository;
        $this->userManager = $userManager;
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

        // Création du user
        $user = $this->userManager->createUser();
        $user->setUsername(UserHelper::generateUsername($newCustomer->getManagerFirstName(), $newCustomer->getManagerLastName()));
        $user->setEmail($newCustomer->getManagerMail());
        $user->setEnabled(1);
        $user->setPlainPassword("admin");
        $this->userManager->updateUser($user);

        return $user;
    }


}