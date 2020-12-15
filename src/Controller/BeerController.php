<?php declare(strict_types=1);

namespace App\Controller;

use App\Constraints\AddBeerToTastingRoomConstraints;
use App\Constraints\GetBeersConstraints;
use App\Service\BeerService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;

class BeerController extends AbstractBaseController
{
    /**
     * @OA\Get(
     *     tags={"Beer"},
     *     summary="Get all beers for tasting room",
     *     path="/api/beers/{tastingRoomId}",
     * )
     */
    public function listBeersForTastingRoom(Request $request, BeerService $beerService): JsonResponse
    {
        $this->_validatorService->validateArray(
            $data = $request->attributes->all(),
            GetBeersConstraints::get()
        );

        $beers = $beerService->getAllBeersForTastingRoom($data['tastingRoomId']);

        $serializedBeers = $this->_serializer->normalize($beers, 'array', [
            'groups' => 'beer:get'
        ]);

        return new JsonResponse($serializedBeers, JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     tags={"Beer"},
     *     summary="Add beer to tasting room",
     *     path="/api/beers/tasting-rooms",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               @OA\Property(
     *                   property="beerName",
     *                   type="name"
     *               ),
     *               @OA\Property(
     *                   property="tastingRoomId",
     *                   type="integer"
     *               )
     *           )
     *       )
     *    ),
     * )
     */
    public function addBeerToTastingRoom(Request $request, BeerService $beerService)
    {
        $this->_validatorService->validateArray(
            $data = json_decode($request->getContent(), true),
            AddBeerToTastingRoomConstraints::get()
        );

        $beer = $beerService->addBeerToTastingRoom(
            $data['beerName'],
            $data['tastingRoomId'],
            $this->getUser()->getId()
        );
        $serializedBeer = $this->_serializer->normalize($beer, 'array', [
            'groups' => 'beer:add-tasting-room'
        ]);

        return new JsonResponse($serializedBeer, JsonResponse::HTTP_CREATED);
    }
}
