<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @OA\Info(title="GrudlannApp",
 *     version="1")
 */
class UserController extends AbstractController
{
    /**
     * @OA\Post(
     *     tags={"User"},
     *     summary="Registration",
     *     path="/api/users/register",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               @OA\Property(
     *                   property="email",
     *                   type="string",
     *               ),
     *               @OA\Property(
     *                   property="nick",
     *                   type="string",
     *                   uniqueItems=true
     *               ),
     *               @OA\Property(
     *                   property="password",
     *                   type="string",
     *                   example="Testowe123!"
     *               ),
     *           )
     *       )
     *   ),
     * )
     *
     */
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
