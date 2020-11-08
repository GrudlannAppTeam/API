<?php

namespace App\Controller;

use App\Constraints\CreateTastingRoomConstraints;
use App\Service\TastingRoomService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;

class TastingRoomController extends AbstractBaseController
{
    /**
     * @OA\Post(
     *     tags={"Tasting Room"},
     *     summary="Create",
     *     path="/api/tasting-rooms",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               @OA\Property(
     *                   property="name",
     *                   type="string"
     *               )
     *           )
     *       )
     *    ),
     * )
     *
     */
    public function create(Request $request, TastingRoomService $tastingRoomService)
    {
        $this->_validatorService->validateArray(
            $data = json_decode($request->getContent(), true),
            CreateTastingRoomConstraints::get()
        );

        $tastingRoom = $tastingRoomService->createTastingRoom($data['name']);

        $serializedTastingRoom = $this->_serializer->normalize($tastingRoom, 'array', [
            'groups' => 'tasting-room:post'
        ]);

        return new JsonResponse($serializedTastingRoom, JsonResponse::HTTP_CREATED);
    }
}
