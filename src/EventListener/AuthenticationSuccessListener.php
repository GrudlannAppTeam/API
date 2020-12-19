<?php declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use App\Service\ValidatorService;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AuthenticationSuccessListener
{
    private $validatorService;
    private $serializer;
    private $em;

    public function __construct(ValidatorService $validatorService, EntityManagerInterface $em)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $this->serializer = new Serializer([$normalizer]);
        $this->validatorService = $validatorService;
        $this->em = $em;
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        /** @var User $user */
        $user = $event->getUser();

        if ($user->getTastingRoom()) {
            $serializedTastingRoom = $this->serializer->normalize($user->getTastingRoom(), 'array', [
                'groups' => 'tasting-room:get:active'
            ]);
        }

        $event->setData([
            'userId' => $user->getId(),
            'nick' => $user->getNick(),
            'token' => $event->getData()['token'],
            'tastingRoom' => $serializedTastingRoom ?? null
        ]);
    }
}