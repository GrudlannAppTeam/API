<?php declare(strict_types=1);

namespace App\EventListener;

use App\Email\Mailer;
use App\Entity\User;
use App\Service\TokenGeneratorService;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UserRegisterListener
{
    private $tokenGenerator;
    private $mailer;

    public function __construct(TokenGeneratorService $tokenGenerator, Mailer $mailer)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof User) {
            return;
        }
        $em = $args->getObjectManager();
        $entity->setConfirmationToken(
            $this->tokenGenerator->getRandomSecureToken()
        );

        $em->flush();

        $this->mailer->sendConfirmationEmail($entity);
    }
}
