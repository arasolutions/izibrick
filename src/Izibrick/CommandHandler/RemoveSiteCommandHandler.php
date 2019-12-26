<?php


namespace App\Izibrick\CommandHandler;

use App\Entity\Invoice;
use App\Entity\Site;
use App\Entity\TrackingQuote;
use App\Entity\User;
use App\Enum\SiteStatus;
use App\Izibrick\Command\AddTrackingQuoteCommand;
use App\Helper\StripeHelper;
use App\Repository\InvoiceRepository;
use App\Repository\SiteRepository;
use App\Repository\TrackingQuoteRepository;
use App\Repository\UserRepository;
use App\Repository\UserSiteRepository;
use FOS\UserBundle\Model\UserManagerInterface;
use Imagine\Filter\Basic\Strip;

/**
 * Class RemoveSiteCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class RemoveSiteCommandHandler
{
    /** @var SiteRepository $siteRepository */
    private $siteRepository;

    /** @var UserRepository $userRepository */
    private $userRepository;

    /** @var UserSiteRepository $userSiteRepository */
    private $userSiteRepository;

    /** @var UserManagerInterface $userManager */
    private $userManager;

    /**
     * RemoveSiteCommandHandler constructor.
     * @param SiteRepository $siteRepository
     * @param UserRepository $userRepository
     * @param UserSiteRepository $userSiteRepository
     * @param UserManagerInterface $userManager
     */
    public function __construct(SiteRepository $siteRepository, UserRepository $userRepository, UserSiteRepository $userSiteRepository, UserManagerInterface $userManager)
    {
        $this->siteRepository = $siteRepository;
        $this->userRepository = $userRepository;
        $this->userSiteRepository = $userSiteRepository;
        $this->userManager = $userManager;
    }

    /**
     * @param User $user
     * @param Site $site
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(User $user, Site $site)
    {

        // Suppression du lien User - Site
        $userSite = $this->userSiteRepository->findOneBy(array(
            'user' => $user,
            'site' => $site
        ));

        if ($userSite != null) {
            $userSite->setActive(false);
            $this->userSiteRepository->save($userSite);
        }

        // Mise du site à l'état DESACTIVE
        $site->setStatus(SiteStatus::DESACTIVE['name']);
        $this->siteRepository->save($site);

        // Annulation de son abonnement
        $stripe = new StripeHelper();
        $stripe->cancelSubscription($site->getStripeSubscriptionId());

        // Mise de l'utilisateur à l'état DESACTIVE si un seul site ACTIF
        $sitesEncoreActifs = $this->siteRepository->findAllActiveSiteByUser($user);
        if (sizeof($sitesEncoreActifs) == 0) {
            $user->setActive(false);
            $this->userManager->updateUser($user);
        }

        // Préparation de la dernière facture - INUTILE


    }
}