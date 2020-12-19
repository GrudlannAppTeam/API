<?php declare(strict_types=1);

namespace App\Controller;

use App\Constraints\CreateUserConstraints;
use App\Service\UserService;
use App\Service\ValidatorService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractBaseController
{
    private $validatorService;

    private $userService;

    public function __construct(ValidatorService $validatorService, UserService $userService)
    {
        parent::__construct($validatorService);
        $this->validatorService = $validatorService;
        $this->userService = $userService;
    }

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
    public function register(Request $request): JsonResponse
    {
        $this->_validatorService->validateArray(
            $data = json_decode($request->getContent(), true),
            CreateUserConstraints::get()
        );

        $user = $this->userService->createUser($data['email'], $data['password'], $data['nick']);

        $serializedUser = $this->_serializer->normalize($user, 'array', [
            'groups' => 'user:post'
        ]);

        return new JsonResponse($serializedUser, JsonResponse::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     tags={"User"},
     *     summary="Get all users",
     *     path="/api/users",
     * )
     */
    public function getUsers(): JsonResponse
    {
        $users = $this->userService->getUsers();

        $serializedUsers = $this->_serializer->normalize($users, 'array', [
            'groups' => 'user:get'
        ]);

        return new JsonResponse($serializedUsers, JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     tags={"User"},
     *     summary="Confirm user by token - via email",
     *     path="/api/users/confirm/{token}",
     * )
     */
    public function confirm(string $token, UserService $userService): JsonResponse
    {
        $userService->confirmUser($token);

        return new JsonResponse('OK', JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     tags={"User"},
     *     summary="Get user by id",
     *     path="/api/users/{id}",
     * )
     */
    public function getUserById(int $id, UserService $userService): JsonResponse
    {
        $user = $userService->getUserById($id);

        if ($user->getTastingRoom()) {
            $serializedData = $this->_serializer->normalize($user->getTastingRoom(), 'array', [
                'groups' => 'tasting-room:get:active'
            ]);
        } else {
            $serializedData = $this->_serializer->normalize($user, 'array', [
                'groups' => 'user:get'
            ]);
        }

        return new JsonResponse($serializedData, JsonResponse::HTTP_OK);
    }
}
