<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Beer;
use App\Entity\TastingRoom;
use App\Entity\User;
use App\Exception\NotFoundException;
use App\Exception\TastingRoomOwnerException;
use App\Repository\BeerRepository;
use App\Repository\TastingRoomRepository;
use Doctrine\ORM\EntityManagerInterface;

class BeerService
{
    private $beerRepository;
    private $em;
    private $tastingRoomRepository;

    public function __construct(BeerRepository $beerRepository, TastingRoomRepository $tastingRoomRepository, EntityManagerInterface $em)
    {
        $this->beerRepository = $beerRepository;
        $this->em = $em;
        $this->tastingRoomRepository = $tastingRoomRepository;
    }

    public function createBeer(string $name): Beer
    {
        $beer = new Beer($name);

        $this->em->persist($beer);
        $this->em->flush();

        return $beer;
    }

    public function getAllBeersForTastingRoom($tastingRoomId): array
    {
        return $this->beerRepository->findBeersBelongsToTastingRoom($tastingRoomId);
    }

    public function addBeerToTastingRoom(string $beerName, int $tastingRoomId, int $userId): TastingRoom
    {
        $beer = new Beer($beerName);
        $tastingRoom = $this->tastingRoomRepository->find($tastingRoomId);

        if ($tastingRoom->getOwner() === $userId) {
            throw new TastingRoomOwnerException();
        }

        if (!$beer || !$tastingRoom) {
            throw new NotFoundException();
        }

        $beer->setTastingRoom($tastingRoom);

        $this->em->persist($beer);
        $this->em->flush();

        return $tastingRoom;
    }
}