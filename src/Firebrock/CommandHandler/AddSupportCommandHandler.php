<?php


namespace App\Firebrock\CommandHandler;


use App\Entity\Site;
use App\Entity\Support;
use App\Entity\User;
use App\Firebrock\Command\AddSupportCommand;
use App\Firebrock\Command\GlobalParametersCommand;
use App\Firebrock\Command\HomeCommand;
use App\Firebrock\Command\PresentationCommand;
use App\Repository\SiteRepository;
use App\Repository\SupportRepository;
use App\Repository\UserRepository;

/**
 * Class AddSupportCommandHandler
 * @package App\Firebrock\CommandHandler
 */
class AddSupportCommandHandler
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var SupportRepository */
    private $supportRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     * @param UserRepository $userRepository
     * @param SupportRepository $supportRepository
     */
    public function __construct(SiteRepository $siteRepository, SupportRepository $supportRepository, UserRepository $userRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->userRepository = $userRepository;
        $this->supportRepository = $supportRepository;
    }

    /**
     * @param AddSupportCommand $command
     * @param User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(AddSupportCommand $command, User $user)
    {
        $support = new Support();
        $support->setType($command->type);
        $support->setType($command->content);
        $support->setUser($user);
        $this->supportRepository->save($support);
    }

}