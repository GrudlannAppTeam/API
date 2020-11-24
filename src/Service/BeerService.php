<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Beer;
use App\Repository\BeerRepository;
use Doctrine\ORM\EntityManagerInterface;

class BeerService
{
    private $beerRepository;
    private $em;

    public function __construct(BeerRepository $beerRepository, EntityManagerInterface $em)
    {
        $this->beerRepository = $beerRepository;
        $this->em = $em;
    }

    public function createBeer($name): Beer
    {
        $beer = new Beer($name);

        $this->em->persist($beer);
        $this->em->flush();

        return $beer;
    }

    public function getAllBeers(): array
    {
        return $this->beerRepository->findAll();
    }
}