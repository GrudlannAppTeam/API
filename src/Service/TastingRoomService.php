<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\TastingRoom;
use App\Entity\User;
use App\Exception\UserHasRoomException;
use App\Repository\TastingRoomRepository;
use App\Utils\CodeGenerator;
use Doctrine\ORM\EntityManagerInterface;

class TastingRoomService
{
    private $tastingRoomRepository;
    private $em;
    private $codeGenerator;

    public function __construct(TastingRoomRepository $tastingRoomRepository, EntityManagerInterface $em, CodeGenerator $codeGenerator)
    {
        $this->tastingRoomRepository = $tastingRoomRepository;
        $this->em = $em;
        $this->codeGenerator = $codeGenerator;
    }

    public function createTastingRoom(string $name, User $owner): TastingRoom
    {
        $this->checkIfUserHasRoom($owner->getId());

        $tastingRoom = new TastingRoom(
            $name,
            $this->codeGenerator->generate(6),
            $owner
        );

        $this->em->persist($tastingRoom);
        $this->em->flush();

        return $tastingRoom;
    }

    public function getTastingRooms(): array
    {
        return $this->tastingRoomRepository->findAll();
    }

    public function checkIfUserHasRoom(int $userId): void
    {
        $tastingRoom = $this->tastingRoomRepository->checkIfUserHasRoom($userId);

        if ($tastingRoom !== null) {
            throw new UserHasRoomException($tastingRoom->getId());
        }
    }
}
