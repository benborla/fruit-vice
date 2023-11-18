<?php

namespace App\EventListener;

use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Console\Command\Command;

/**
 * This class notifies the admin once the fruits:fetch command has been
 * executed successfully.
 *
 * @class NotifyAdminListener
 */
final class NotifyAdminListener
{
    private const EXECUTED_COMMAND = 'fruits:fetch';

    public function __construct(
        private string $adminEmail,
        private string $mailFrom,
        private MailerInterface $mailer,
    ) {
    }

    public function onConsoleTerminate(ConsoleTerminateEvent $event): void
    {
        // @INFO: Make sure to only listen to `fruits:fetch` command
        if (self::EXECUTED_COMMAND !== $event->getCommand()->getName()) {
            // @INFO: Do nothing
            return;
        }

        if ($event->getExitCode() !== Command::SUCCESS) {
            // @INFO: Do nothing
            return;
        }

        $email = (new Email())
            ->from($this->mailFrom)
            ->to($this->adminEmail)
            ->subject('FruityVice Fetch From API | ' . date('Y-m-d H:i:s'))
            ->text('Hi Admin, the app has fetched a new data.');

        $this->mailer->send($email);
    }
}
