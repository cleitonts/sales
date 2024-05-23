<?php

namespace Panthir\Infrastructure\Messenger\Handler;

use Panthir\Infrastructure\Messenger\DTO\EmailNotification;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EmailNotificationHandler
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function __invoke(EmailNotification $message): void
    {
        $email = (new TemplatedEmail())
            ->to($message->userEmail)
            ->subject('Password recover')
            ->htmlTemplate('emails/resetPassword.html.twig')
            ->context([
                'url' => 'http://localhost:5173/account/resetPassword/'.$message->resetPasswordtoken,
            ])
        ;

        $this->mailer->send($email);
    }
}
