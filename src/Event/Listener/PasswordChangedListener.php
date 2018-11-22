<?php
declare(strict_types=1);

namespace App\Event\Listener;

use App\Event\PasswordChangedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

class PasswordChangedListener implements EventSubscriberInterface
{
    private $logger;
    private $twig;

    public function __construct(
        LoggerInterface $logger,
        Environment $twig
    ) {
        $this->logger = $logger;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PasswordChangedEvent::NAME => 'onPasswordChanged'
        ];
    }

    /**
     * Inject needed dependencies in constructor
     * Could be used for sending password change notification to user
     */
    public function onPasswordChanged(PasswordChangedEvent $event): void
    {
        $user = $event->getUser();

        $parameters = [];
        $parameters['email'] = $user->getEmail();

        $body = $this->twig->render('email/password_changed.html.twig', $parameters);

        // at least log a message to confirm listener is being invoked
        $this->logger->debug('Sending password changed email with body: ' . $body);

        // TODO: send email
    }
}
