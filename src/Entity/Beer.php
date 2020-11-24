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
     * @Groups({"beer:post", "beer:get", "beer:add-tasting-room"})
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Groups({"beer:post", "beer:get"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="TastingRoom", mappedBy="beers")
     */
    private $tastingRooms;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->tastingRooms = new ArrayCollection();
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

    public function getTastingRooms(): Collection
    {
        return $this->tastingRooms;
    }

    public function addTastingRoom(TastingRoom $tastingRoom): self
    {
        if (!$this->tastingRooms->contains($tastingRoom)) {
            $this->tastingRooms[] = $tastingRoom;
            $tastingRoom->addBeer($this);
        }

        return $this;
    }

    public function removeTastingRoom(TastingRoom $tastingRoom): self
    {
        if ($this->tastingRooms->contains($tastingRoom)) {
            $this->tastingRooms->removeElement($tastingRoom);
            $tastingRoom->removeBeer($this);
        }

        return $this;
    }
}
