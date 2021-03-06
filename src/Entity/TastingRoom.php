<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TastingRoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TastingRoomRepository::class)
 */
class TastingRoom
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups({"tasting-room:post", "tasting-room:get", "beer:add-tasting-room", "tasting-room:join", "tasting-room:get:active", "review:statistic"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     *
     * @Groups({"tasting-room:post", "tasting-room:get", "tasting-room:join", "tasting-room:get:active", "review:statistic"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=6)
     *
     * @Groups({"tasting-room:post", "tasting-room:get", "tasting-room:get:active"})
     */
    private $code;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Groups({"tasting-room:post", "tasting-room:get", "tasting-room:get:active"})
     */
    private $isStart;

    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     *
     * @Groups({"tasting-room:post", "tasting-room:get", "tasting-room:join", "tasting-room:get:active"})
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="tastingRoom")
     *
     * @Groups({"tasting-room:join", "tasting-room:get:active"})
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="Beer", mappedBy="tastingRoom", orphanRemoval=true)
     *
     * @Groups({"beer:add-tasting-room", "tasting-room:join", "tasting-room:get:active"})
     */
    private $beers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="tastingRoom", orphanRemoval=true)
     */
    private $reviews;

    public function __construct(string $name, string $code, User $owner)
    {
        $this->name = $name;
        $this->code = $code;
        $this->owner = $owner;
        $this->users = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner($owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setTastingRoom($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            if ($user->getTastingRoom() === $this) {
                $user->setTastingRoom(null);
            }
        }

        return $this;
    }

    public function getBeers(): Collection
    {
        return $this->beers;
    }

    public function addBeer(Beer $beer): TastingRoom
    {
        if (!$this->beers->contains($beer)) {
            $this->beers[] = $beer;
        }

        return $this;
    }

    public function removeBeer(Beer $beer): self
    {
        if ($this->beers->contains($beer)) {
            $this->beers->removeElement($beer);
        }

        return $this;
    }

    public function isStart(): ?bool
    {
        return $this->isStart;
    }

    public function setIsStart(bool $isStart): self
    {
        $this->isStart = $isStart;
        return $this;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setTastingRoom($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            if ($review->getTastingRoom() === $this) {
                $review->setTastingRoom(null);
            }
        }

        return $this;
    }
}
