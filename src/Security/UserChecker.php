<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security;

use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * UserChecker checks the user account flags.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class UserChecker implements UserCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function checkPreAuth(UserInterface $user)
    {
        // LEGAGY
        if (!$user instanceof AdvancedUserInterface && !$user instanceof User) {
            return;
        }

        if ($user instanceof AdvancedUserInterface && !$user instanceof User) {
            @trigger_error(sprintf('Calling "%s()" with an AdvancedUserInterface is deprecated since Symfony 4.1. Create a custom user checker if you wish to keep this functionality.', __METHOD__), E_USER_DEPRECATED);
        }

        if (!$user->isAccountNonLocked()) {
            $ex = new LockedException('Voter compte est bloqué');
            $ex->setUser($user);
            throw $ex;
        }

        if (!$user->isEnabled()) {
            $ex = new DisabledException('Votre compte est désactivé');
            $ex->setUser($user);
            throw $ex;
        }

        if (!$user->isAccountNonExpired()) {
            $ex = new AccountExpiredException('Votre compte a expiré');
            $ex->setUser($user);
            throw $ex;
        }

        // IZIBRICK
        $sites = $user->getSites();
        if (sizeof($sites) == 0) {
            $ex =  new AuthenticationException('Vous n\'avez aucun site actif.');
            throw $ex;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AdvancedUserInterface && !$user instanceof User) {
            return;
        }

        if ($user instanceof AdvancedUserInterface && !$user instanceof User) {
            @trigger_error(sprintf('Calling "%s()" with an AdvancedUserInterface is deprecated since Symfony 4.1. Create a custom user checker if you wish to keep this functionality.', __METHOD__), E_USER_DEPRECATED);
        }

        if (!$user->isCredentialsNonExpired()) {
            $ex = new CredentialsExpiredException('Vous devez modifier votre mot de passe');
            $ex->setUser($user);
            throw $ex;
        }
    }
}
