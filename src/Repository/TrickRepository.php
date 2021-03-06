<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }


    /**
     * @return Trick[]
     */
    public function getTricks(): array
    {
        return $this->createQueryBuilder('t')
            ->addOrderBy('t.createdAt', 'desc')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $id
     * @return Trick
     * @throws EntityNotFoundException
     */
    public function getTrick($id): Trick
    {
        $trick = $this->createQueryBuilder('t')
            ->andWhere('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        if (!$trick) {
            throw new EntityNotFoundException('Trick with id ' . $id . ' is not found');
        }

        return $trick[0];
    }
}
