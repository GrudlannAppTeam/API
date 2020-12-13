<?php declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use App\Service\TokenGeneratorService;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UserRegisterListener
{
    private $tokenGenerator;

    public function __construct(TokenGeneratorService $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
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

        //@TODO SEND EMAIL
    }
}
