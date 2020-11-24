<?php declare(strict_types=1);

namespace App\Controller;

use App\Constraints\AddBeerToTastingRoomConstraints;
use App\Constraints\CreateBeersConstraints;
use App\Service\BeerService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;

class BeerController extends AbstractBaseController
{
    /**
     * @OA\Post(
     *     tags={"Beer"},
     *     summary="Create",
     *     path="/api/beers",
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
    public function create(Request $request, BeerService $beerService): JsonResponse
    {
        $this->_validatorService->validateArray(
            $data = json_decode($request->getContent(), true),
            CreateBeersConstraints::get()
        );

        $beer = $beerService->createBeer($data['name']);
        $serializedBeer = $this->_serializer->normalize($beer, 'array', [
            'groups' => 'beer:post'
        ]);

        return new JsonResponse($serializedBeer, JsonResponse::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     tags={"Beer"},
     *     summary="Get all beers",
     *     path="/api/beers",
     * )
     */
    public function list(BeerService $beerService): JsonResponse
    {
        $beers = $beerService->getAllBeers();
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
     *                   property="beerId",
     *                   type="integer"
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
            $data['beerId'],
            $data['tastingRoomId'],
            $this->getUser()->getId()
        );
        $serializedBeer = $this->_serializer->normalize($beer, 'array', [
            'groups' => 'beer:add-tasting-room'
        ]);

        return new JsonResponse($serializedBeer, JsonResponse::HTTP_CREATED);
    }
}
