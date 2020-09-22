<?php

namespace App\Repository;

use App\Entity\Proccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Proccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method Proccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method Proccess[]    findAll()
 * @method Proccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProccessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proccess::class);
    }

    // /**
    //  * @return Proccess[] Returns an array of Proccess objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Proccess
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
