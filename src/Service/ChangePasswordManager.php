<?php

namespace App\Service;

use App\Entity\User;
use App\Event\PasswordChangedEvent;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ChangePasswordManager
{
    private $userPasswordEncoder;
    private $eventDispatcher;
    private $entityManager;
    private $userRepository;

    public function __construct(
        UserPasswordEncoderInterface $userPasswordEncoder,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ) {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function changePassword(User $user, $password): bool
    {
        $encodedPassword = $this->userPasswordEncoder->encodePassword($user, $password);
        // update current session password
        $user->setPassword($encodedPassword);

        // update DB user password
        /** @var User $dbUser */
        $dbUser = $this->userRepository->findOneBy(['email' => $user->getEmail()]);
        $dbUser->setPassword($user->getPassword());
        $this->entityManager->flush();

        $event = new PasswordChangedEvent($dbUser);
        $this->eventDispatcher->dispatch(PasswordChangedEvent::NAME, $event);

        return true;
    }
}
