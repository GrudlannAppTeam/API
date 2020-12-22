<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups({"review:post"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reviews")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TastingRoom", inversedBy="reviews")
     */
    private $tastingRoom;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Beer", inversedBy="reviews")
     */
    private $beer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Answer")
     */
    private $answer;

    public function __construct(User $user, TastingRoom $tastingRoom, Beer $beer, Answer $answer)
    {
        $this->user = $user;
        $this->tastingRoom = $tastingRoom;
        $this->beer = $beer;
        $this->answer = $answer;
    }

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

    public function getTastingRoom(): TastingRoom
    {
        return $this->tastingRoom;
    }

    public function setTastingRoom(TastingRoom $tastingRoom): self
    {
        $this->tastingRoom = $tastingRoom;
        return $this;
    }

    public function getBeer(): Beer
    {
        return $this->beer;
    }

    public function setBeer(Beer $beer): self
    {
        $this->beer = $beer;
        return $this;
    }

    public function getAnswer(): Answer
    {
        return $this->answer;
    }

    public function setAnswer(Answer $answer): self
    {
        $this->answer = $answer;
        return $this;
    }
}
