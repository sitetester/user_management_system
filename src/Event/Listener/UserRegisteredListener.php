<?php
declare(strict_types=1);

namespace App\Event\Listener;

use App\Entity\User;
use App\Event\UserRegisteredEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegisteredListener implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Another way to register listener.
     * Previously UserPrePersistListener was registered using Doctrine prePersist event
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::NAME => 'onUserRegistered'
        ];
    }

    /**
     * Could be used for sending new user registration email to provide further functionality
     *
     * @param UserRegisteredEvent $event
     */
    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        // do the same stuff as done in PasswordChangedListener
        // at least log a message to confirm listener is being invoked
        $this->logger->debug('New user registered with email: ' . $user->getEmail());
        
        // TODO: send notification
    }
}
