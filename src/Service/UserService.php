<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Exception\NotFoundException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $userRepository;

    private $em;

    private $encoder;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->encoder = $encoder;
    }

    public function createUser(string $email, string $password, string $nick): User
    {
        $user = new User($email, $nick);
        $user->setPassword($this->encoder->encodePassword($user, $password));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function getUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function confirmUser(string $confirmationToken): void
    {
        $user = $this->userRepository->findOneBy(['confirmationToken' => $confirmationToken]);

        if (!$user) {
            throw new NotFoundException($confirmationToken);
        }

        $user->setEnabled(true);
        $user->setConfirmationToken(null);
        $this->em->flush();
    }
}
