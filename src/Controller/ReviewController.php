<?php declare(strict_types=1);

namespace App\Controller;

use App\Constraints\CreateReviewConstraints;
use App\Service\ReviewService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ReviewController extends AbstractBaseController
{
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
