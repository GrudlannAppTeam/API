<?php declare(strict_types=1);

namespace App\Email;

use App\Entity\User;
use Twig\Environment;

class Mailer
{
    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendConfirmationEmail(User $user)
    {
        $body = $this->twig->render(
            'email/confirmation.html.twig', [
                'user' => $user
            ]
        );
        $message = (new \Swift_Message('Please confirm your account'))
            ->setFrom('grudlannapp@gmail.com')
            ->setTo($user->getEmail())
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }
}
