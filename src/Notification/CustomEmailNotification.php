<?php 
namespace App\Notification;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class CustomEmailNotification
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmailNotification(string $recipientEmail, string $subject, string $content): void
    {
        $email = (new Email())
            ->from('forhopeplay@gmail.com')
            ->to($recipientEmail)
            ->subject($subject)
            ->text($content);

        $this->mailer->send($email);
    }
}
