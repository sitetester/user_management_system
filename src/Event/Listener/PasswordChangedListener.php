<?php

namespace App\Event\Listener;

use App\Event\PasswordChangedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PasswordChangedListener implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PasswordChangedEvent::NAME => 'onPasswordChanged'
        ];
    }

    public function onPasswordChanged(): void
    {
        // TODO: send email
        $this->logger->info('Password changed email sent successfully!');
    }
}
