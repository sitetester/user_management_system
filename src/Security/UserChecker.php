<?php
declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * https://symfony.com/doc/current/security/user_checkers.html
 */
class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        /** @var User $user */
        if ($user->getDisabled()) {
            throw new CustomUserMessageAuthenticationException(
                'Your account is disabled. Please contact administrator!'
            );
        }

        // perform other checks e.g account deleted
    }

    public function checkPostAuth(UserInterface $user): void
    {
        /** @var User $user */
        // perform other checks, e.g  user account is expired
    }
}
