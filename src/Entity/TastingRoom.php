<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TastingRoomRepository;
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
     * @Groups({"tasting-room:post", "tasting-room:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     *
     * @Groups({"tasting-room:post", "tasting-room:get"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=6)
     *
     * @Groups({"tasting-room:post", "tasting-room:get"})
     */
    private $code;

    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     *
     * @Groups({"tasting-room:post", "tasting-room:get"})
     */
    private $owner;

    public function __construct(string $name, string $code, User $owner)
    {
        $this->name = $name;
        $this->code = $code;
        $this->owner = $owner;
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

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner($owner): self
    {
        $this->owner = $owner;
        return $this;
    }
}
