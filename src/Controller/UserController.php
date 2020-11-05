<?php declare(strict_types=1);

namespace App\Controller;

use App\Constraints\CreateUserConstraints;
use App\Service\UserService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractBaseController
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
     *                   uniqueItems=true
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
     *    ),
     * )
     *
     */
    public function register(Request $request, UserService $userService): JsonResponse
    {
        $this->_validatorService->validateArray(
            $data = json_decode($request->getContent(), true),
            CreateUserConstraints::get()
        );

        $user = $userService->createUser($data['email'], $data['password'], $data['nick']);

        $serializedUser = $this->_serializer->normalize($user, 'array', [
            'groups' => 'user:post'
        ]);

        return new JsonResponse($serializedUser, JsonResponse::HTTP_CREATED);
    }
}
