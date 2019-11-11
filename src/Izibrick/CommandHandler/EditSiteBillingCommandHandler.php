<?php


namespace App\Izibrick\CommandHandler;


use App\Entity\Blog;
use App\Entity\Contact;
use App\Entity\Home;
use App\Entity\Presentation;
use App\Entity\Quote;
use App\Entity\Site;
use App\Entity\User;
use App\Helper\ColorHelper;
use App\Helper\StripeHelper;
use App\Izibrick\Command\AddSiteCommand;
use App\Izibrick\Command\SiteBillingCommand;
use App\Repository\BlogRepository;
use App\Repository\CodePromotionRepository;
use App\Repository\ContactRepository;
use App\Repository\HomeRepository;
use App\Repository\PresentationRepository;
use App\Repository\ProductRepository;
use App\Repository\QuoteRepository;
use App\Repository\SiteRepository;
use App\Repository\TemplateRepository;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class EditSiteBillingCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class EditSiteBillingCommandHandler
{
    /** @var UserManagerInterface */
    private $userManager;

    /**
     * EditSiteBillingCommandHandler constructor.
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param SiteBillingCommand $command
     * @param $userId
     * @return mixed
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function handle(SiteBillingCommand $command, $userId)
    {
        /** @var User $user */
        $user = $this->userManager->findUserBy(
            ['id' => $userId]
        );

        $user->setAddress1($command->getAddress1());
        $user->setAddress2($command->getAddress2());
        $user->setSocietyName(strtoupper($command->getSocietyName()));
        $user->setPostalCode($command->getPostalCode());
        $user->setCity(strtoupper($command->getCity()));

        $this->userManager->updateUser($user);

        $stripe = new StripeHelper();
        // Mise à jour des données postales
        $stripe->updateCustomer(
            $user->getStripeCustomerId(),
            $user->getSocietyName(),
            $user->getId() . '-' . $user->getEmail(),
            $user->getEmail(),
            $user->getAddress1(),
            $user->getAddress2(),
            $user->getCity(),
            $user->getPostalCode()
        );

        return $user;
    }


}