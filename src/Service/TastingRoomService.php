<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\TastingRoom;
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

    public function createTastingRoom(string $name): TastingRoom
    {
        $tastingRoom = new TastingRoom(
            $name,
            $this->codeGenerator->generate(6)
        );

        $this->em->persist($tastingRoom);
        $this->em->flush();

        return $tastingRoom;
    }

    public function getTastingRooms(): array
    {
        return $this->tastingRoomRepository->findAll();
    }
}
