<?php


namespace App\Firebrock\CommandHandler;


use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Firebrock\Command\AddSiteCommand;
use App\Firebrock\Command\RegistrationCommand;
use App\Repository\SiteRepository;
use App\Repository\UserSiteRepository;

class CreateUserCommandHandler
{


    public function handle(RegistrationCommand $command, User $user)
    {
        if ($user != null) {
            $user->setEmail($command->getEmail());
            $user->setPlainPassword($command->getPlainPassword());
            $user->setFirstname($command->getFirstname());
            $user->setLastname($command->getLastname());
            $user->setPhone($command->getPhone());

            return $user;
        }
        return null;
    }
}