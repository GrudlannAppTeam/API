<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\BeerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BeerRepository::class)
 */
class Beer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups({"beer:post", "beer:get", "beer:add-tasting-room", "tasting-room:join", "tasting-room:get:active"})
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Groups({"beer:post", "beer:get", "tasting-room:join", "tasting-room:get:active"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="TastingRoom", inversedBy="beers")
     */
    private $tastingRoom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="beer")
     */
    private $reviews;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getTastingRoom(): ?TastingRoom
    {
        return $this->tastingRoom;
    }

    public function setTastingRoom($tastingRoom): self
    {
        $this->tastingRoom = $tastingRoom;
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
            $review->setBeer($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            if ($review->getBeer() === $this) {
                $review->setBeer(null);
            }
        }

        return $this;
    }
}
