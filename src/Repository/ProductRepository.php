<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

        public function findOneByName($value)
        {
            return $this->createQueryBuilder('p')
                ->andWhere( 'p.name = :val')
                ->setParameter('val', $value)
                ->setMaxResults(10)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }

        public function findOneById($id)
        {
            return $this->createQueryBuilder('p')
                ->andWhere( 'p.id = :id')
                ->setParameter('id', $id)
//                ->setMaxResults(10)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }

}
