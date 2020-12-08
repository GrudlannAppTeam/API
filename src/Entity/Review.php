<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reviews")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="review")
     */
    private $questions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TastingRoom", inversedBy="reviews")
     */
    private $tastingRoom;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser($user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getQuestions()
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setReview($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            if ($question->getReview() === $this) {
                $question->setReview(null);
            }
        }

        return $this;
    }

    public function getTastingRoom(): TastingRoom
    {
        return $this->tastingRoom;
    }

    public function setTastingRoom(TastingRoom $tastingRoom): self
    {
        $this->tastingRoom = $tastingRoom;
        return $this;
    }
}
