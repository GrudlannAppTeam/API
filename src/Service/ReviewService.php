<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Review;
use App\Entity\User;
use App\Exception\NotFoundException;
use App\Repository\BeerRepository;
use App\Repository\QuestionRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReviewService
{
    private $beerRepository;
    private $em;
    private $questionRepository;
    private $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository, QuestionRepository$questionRepository, BeerRepository $beerRepository, EntityManagerInterface $em)
    {
        $this->beerRepository = $beerRepository;
        $this->em = $em;
        $this->questionRepository = $questionRepository;
        $this->reviewRepository = $reviewRepository;
    }

    public function createReview(User $user, int $beerId, int $questionId): Review
    {
        $beer = $this->beerRepository->find($beerId);
        $question = $this->questionRepository->find($questionId);

        if (!$beer) {
            throw new NotFoundException($beerId);
        }

        if (!$question) {
            throw new NotFoundException($questionId);
        }

        $review = new Review(
            $user,
            $user->getTastingRoom(),
            $beer,
            $question
        );

        $this->em->persist($review);
        $this->em->flush();

        return $review;
    }

    public function getQA(): array
    {
        return $this->questionRepository->findAll();
    }
}
