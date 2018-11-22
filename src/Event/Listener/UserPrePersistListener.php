<?php
declare(strict_types=1);

namespace App\Event\Listener;

use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPrePersistListener
{
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * Encodes user's password using configured algorithm
     * See `Encoder` section in /config/packages/security.yaml
     *
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event): void
    {
        /** @var User $user */
        $user = $event->getEntity();

        if ($user instanceof User) {
            $password = $this->userPasswordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
        }
    }
}
