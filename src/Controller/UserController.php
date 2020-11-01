<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User(
            $data['email'],
            $data['nick']
        );

        $user->setPassword($encoder->encodePassword($user, $data['password']));

        $em->persist($user);
        $em->flush();

        return new JsonResponse("it's works", JsonResponse::HTTP_CREATED);
    }
}
