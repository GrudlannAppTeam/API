<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\ReviewService;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Annotations as OA;

class QuestionAnswerController extends AbstractBaseController
{
    /**
     * @OA\Get(
     *     tags={"Question and Answers"},
     *     summary="Get all questions and answers to them",
     *     path="/api/questions/answers",
     * )
     */
    public function getQuestionsAndAnswers(ReviewService $reviewService): JsonResponse
    {
        $qa = $reviewService->getQA();

        $serializedQA = $this->_serializer->normalize($qa, 'array', [
            'groups' => 'review:get:qa'
        ]);

        return new JsonResponse($serializedQA, JsonResponse::HTTP_OK);
    }
}