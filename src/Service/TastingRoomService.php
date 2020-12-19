<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\TastingRoom;
use App\Entity\User;
use App\Exception\NotFoundException;
use App\Exception\TastingRoomIsStartException;
use App\Exception\TastingRoomOwnerException;
use App\Exception\UserHasRoomException;
use App\Repository\TastingRoomRepository;
use App\Repository\UserRepository;
use App\Utils\CodeGenerator;
use Doctrine\ORM\EntityManagerInterface;

class TastingRoomService
{
    private $tastingRoomRepository;
    private $em;
    private $codeGenerator;
    private $userRepository;

    public function __construct(TastingRoomRepository $tastingRoomRepository, UserRepository $userRepository, EntityManagerInterface $em, CodeGenerator $codeGenerator)
    {
        $this->tastingRoomRepository = $tastingRoomRepository;
        $this->em = $em;
        $this->codeGenerator = $codeGenerator;
        $this->userRepository = $userRepository;
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

        $owner->setTastingRoom($tastingRoom);
        $this->em->persist($owner);
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

    public function deleteTastingRoom(int $tastingRoomId, int $ownerId = null): void
    {
        $tastingRoom = $this->tastingRoomRepository->find($tastingRoomId);

        if ($tastingRoom === null) {
            throw new NotFoundException($tastingRoomId);
        }

        if ($tastingRoom->getOwner()->getId() !== $ownerId) {
            throw new TastingRoomOwnerException();
        }

        $user = $this->userRepository->findOneBy(['tastingRoom' => $tastingRoom]);
        $user->setTastingRoom(null);

        $this->em->remove($tastingRoom);
        $this->em->flush();
    }

    public function joinToTastingRoom(string $code, User $user): TastingRoom
    {
        $tastingRoom = $this->tastingRoomRepository->findOneBy(['code' => $code]);

        if ($tastingRoom === null) {
            throw new NotFoundException($code);
        }

        if ($tastingRoom->isStart()) {
            throw new TastingRoomIsStartException();
        }

        $tastingRoom->addUser($user);
        $this->em->persist($tastingRoom);
        $this->em->flush();

        return $tastingRoom;
    }

    public function startTastingRoom(int $tastingRoomId, bool $status): TastingRoom
    {
        $tastingRoom = $this->tastingRoomRepository->find($tastingRoomId);

        if ($tastingRoom === null) {
            throw new NotFoundException($tastingRoomId);
        }

        if ($status === false) {
            $this->removeUsersBelongsToTastingRoom($tastingRoom);
        }

        $tastingRoom->setIsStart($status);
        $this->em->persist($tastingRoom);
        $this->em->flush();

        return $tastingRoom;
    }

    public function getTastingRoomDetailsById(int $tastingRoomId): TastingRoom
    {
        $tastingRoom = $this->tastingRoomRepository->find($tastingRoomId);

        if ($tastingRoom === null) {
            throw new NotFoundException($tastingRoomId);
        }

        return $tastingRoom;
    }

    private function removeUsersBelongsToTastingRoom(TastingRoom $tastingRoom): void
    {
        $users = $tastingRoom->getUsers();

        /** @var User $user */
        foreach ($users as $user) {
            $user->setTastingRoom(null);
        }

        $tastingRoom->setOwner(null);
    }
}
