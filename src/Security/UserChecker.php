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
        // these fields need to be defined in User entity.
        // `deleted` will only change DB status, rather than actually deleting the user.
    }

    /**
     * https://symfony.com/doc/current/event_dispatcher.html#creating-an-event-listener
     *
     * @param UserInterface $user
     */
    public function checkPostAuth(UserInterface $user): void
    {
        // perform other checks, e.g user account is expired, the user may be notified
        // throw AccountExpiredException, create an AppExceptionListener to handle such exceptions
    }
}
