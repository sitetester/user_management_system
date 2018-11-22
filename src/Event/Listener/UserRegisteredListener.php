<?php

namespace App\Event\Listener;

use App\Event\UserRegisteredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegisteredListener implements EventSubscriberInterface
{
    /**
     * Another way to register listener. Previously UserPrePersistListener was registered using Doctrine prePersist event
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::NAME => 'onUserRegistered'
        ];
    }

    public function onUserRegistered(): void
    {
        // do post registration stuff e.g sending registration email
        // TODO: send notification
    }
}
