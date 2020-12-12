<?php declare(strict_types=1);

namespace App\Controller;

use App\Constraints\CreateReviewConstraints;
use App\Service\ReviewService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;

class ReviewController extends AbstractBaseController
{
    /**
     * @OA\Post(
     *     tags={"Review"},
     *     summary="Create review",
     *     path="/api/reviews",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               @OA\Property(
     *                   property="beerId",
     *                   type="integer"
     *               ),
     *               @OA\Property(
     *                   property="questionId",
     *                   type="integer"
     *               )
     *           )
     *       )
     *    ),
     * )
     */
    public function create(Request $request, ReviewService $reviewService): JsonResponse
    {
        $this->_validatorService->validateArray(
            $data = json_decode($request->getContent(), true),
            CreateReviewConstraints::get()
        );

        $review = $reviewService->createReview(
            $this->getUser(),
            $data['beerId'],
            $data['questionId']
        );

        $serializedReview = $this->_serializer->normalize($review, 'array', [
            'groups' => 'review:post'
        ]);

        return new JsonResponse($serializedReview, JsonResponse::HTTP_CREATED);
    }
}
