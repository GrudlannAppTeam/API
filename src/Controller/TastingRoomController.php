<?php

namespace App\Controller;

use App\Constraints\CreateTastingRoomConstraints;
use App\Entity\TastingRoom;
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
    public function create(Request $request, TastingRoomService $tastingRoomService): JsonResponse
    {
        $this->_validatorService->validateArray(
            $data = json_decode($request->getContent(), true),
            CreateTastingRoomConstraints::get()
        );

        $tastingRoom = $tastingRoomService->createTastingRoom($data['name'], $this->getUser());

        $serializedTastingRoom = $this->_serializer->normalize($tastingRoom, 'array', [
            'groups' => 'tasting-room:post'
        ]);

        return new JsonResponse($serializedTastingRoom, JsonResponse::HTTP_CREATED);
    }

    /**
     * @OA\Delete(
     *     tags={"Tasting Room"},
     *     summary="Delete",
     *     path="/api/tasting-rooms/{tastingRoomId}"
     * )
     */
    public function delete(TastingRoom $tastingRoom, TastingRoomService $tastingRoomService): JsonResponse
    {
        $tastingRoomService->deleteTastingRoom(
            $tastingRoom->getId(),
            $this->getUser() ? $this->getUser()->getId() : null
        );

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
