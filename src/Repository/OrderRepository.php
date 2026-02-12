<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

        /**
         * @return Order Returns an array of Order objects
         */
        public function findOneWithRelations(
            int $id
        )
//            : Order
        {
            return $this->createQueryBuilder('o')
                ->select([
                    'o.id',
                    'i.id AS orderItemId',
                    'p.name',
                    'p.price',
                    'i.quantity'
                ])
                ->leftJoin('o.items', 'i')
                ->leftJoin('i.product', 'p')
                ->where('o.id=:id')
                ->setParameter('id', $id)
                ->orderBy('o.id', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getScalarResult()
            ;
        }

    //    public function findOneBySomeField($value): ?Order
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
