<?php

namespace App\Controller;

use App\Constraints\CreateTastingRoomConstraints;
use App\Constraints\GetTastingRoomConstraints;
use App\Constraints\JoinTastingRoomConstraints;
use App\Constraints\StartTastingRoomConstraints;
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

    /**
     * @OA\Post(
     *     tags={"Tasting Room"},
     *     summary="Join",
     *     path="/api/tasting-rooms/join",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               @OA\Property(
     *                   property="code",
     *                   type="string"
     *               )
     *           )
     *       )
     *    ),
     * )
     */
    public function join(Request $request, TastingRoomService $tastingRoomService): JsonResponse
    {
        $this->_validatorService->validateArray(
            $data = json_decode($request->getContent(), true),
            JoinTastingRoomConstraints::get()
        );

        $tastingRoom = $tastingRoomService->joinToTastingRoom(
            $data['code'],
            $this->getUser()
        );

        $serializedTastingRoom = $this->_serializer->normalize($tastingRoom, 'array', [
            'groups' => 'tasting-room:join'
        ]);

        return new JsonResponse($serializedTastingRoom, JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     tags={"Tasting Room"},
     *     summary="Start tasting room",
     *     path="/api/tasting-rooms",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               @OA\Property(
     *                   property="tastingRoomId",
     *                   type="integer"
     *               ),
     *               @OA\Property(
     *                   property="status",
     *                   type="bool"
     *               )
     *           )
     *       )
     *    ),
     * )
     */
    public function start(Request $request, TastingRoomService $tastingRoomService): JsonResponse
    {
        $this->_validatorService->validateArray(
            $data = json_decode($request->getContent(), true),
            StartTastingRoomConstraints::get()
        );

        $tastingRoom = $tastingRoomService->startTastingRoom(
            $data['tastingRoomId'],
            $data['status']
        );

        $serializedTastingRoom = $this->_serializer->normalize($tastingRoom, 'array', [
            'groups' => 'tasting-room:post'
        ]);

        return new JsonResponse($serializedTastingRoom, JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     tags={"Tasting Room"},
     *     summary="Get all tastingRooms",
     *     path="/api/tasting-rooms",
     * )
     */
    public function list(TastingRoomService $tastingRoomService): JsonResponse
    {
        $tastingRooms = $tastingRoomService->getTastingRooms();

        $serializedTastingRooms = $this->_serializer->normalize($tastingRooms, 'array', [
            'groups' => 'tasting-room:get'
        ]);

        return new JsonResponse($serializedTastingRooms, JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     tags={"Tasting Room"},
     *     summary="Get tasting room by id",
     *     path="/api/tasting-rooms/{id}",
     * )
     */
    public function getDetailsById(Request $request, TastingRoomService $tastingRoomService): JsonResponse
    {
        $data['id'] = $request->attributes->get('id');
        $this->_validatorService->validateArray(
            $data = $request->attributes->all(),
            GetTastingRoomConstraints::get()
        );

        $tastingRoom = $tastingRoomService->getTastingRoomDetailsById($data['id']);

        $serializedTastingRoom = $this->_serializer->normalize($tastingRoom, 'array', [
            'groups' => 'tasting-room:get'
        ]);

        return new JsonResponse($serializedTastingRoom, JsonResponse::HTTP_OK);
    }
}
