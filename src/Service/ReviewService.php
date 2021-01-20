<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Review;
use App\Entity\User;
use App\Exception\NotFoundException;
use App\Exception\ReviewExistsException;
use App\Exception\ValidationException;
use App\Repository\AnswerRepository;
use App\Repository\BeerRepository;
use App\Repository\QuestionRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReviewService
{
    private $beerRepository;
    private $em;
    private $reviewRepository;
    private $answerRepository;
    private $questionRepository;

    public function __construct(ReviewRepository $reviewRepository, QuestionRepository $questionRepository, AnswerRepository $answerRepository, BeerRepository $beerRepository, EntityManagerInterface $em)
    {
        $this->beerRepository = $beerRepository;
        $this->em = $em;
        $this->reviewRepository = $reviewRepository;
        $this->answerRepository = $answerRepository;
        $this->questionRepository = $questionRepository;
    }

    public function createReview(User $user, int $beerId, int $answerId): Review
    {
        $beer = $this->beerRepository->find($beerId);
        $answer = $this->answerRepository->find($answerId);

        if (!$beer) {
            throw new NotFoundException($beerId);
        }

        if (!$answer) {
            throw new NotFoundException($answerId);
        }

        $existsReview = $this->reviewRepository->findBy(['user' => $user, 'tastingRoom' => $user->getTastingRoom(), 'beer' => $beer]);

        if ($existsReview) {
            throw new ReviewExistsException();
        }

        $review = new Review(
            $user,
            $user->getTastingRoom(),
            $beer,
            $answer
        );

        $this->em->persist($review);
        $this->em->flush();

        return $review;
    }

    public function getQA(): array
    {
        return $this->questionRepository->findAll();
    }

    public function getStatistics(int $userId): array
    {
        return $this->reviewRepository->findBy(['user' => $userId]);
    }

    public function getStatisticsByTastingRoom(int $userId, int $tastingRoomId): array
    {
        return $this->reviewRepository->findBy(['user' => $userId, 'tastingRoom' => $tastingRoomId]);
    }
}
