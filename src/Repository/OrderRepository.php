<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    /**
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $em
    ) {
        parent::__construct($registry, Order::class);
        $this->em = $em;
    }

    public function save(Order $order)
        : int
    {
        $this->em->persist($order);
        $this->em->flush();

        return $order->getId();
    }

    /**
     * @param int $id
     * @return array
     */
    public function findOneWithRelations(
        int $id
    )
        : array
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

    public function findObjectWithRelations(
        int $id
    )
        : Order
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.items', 'i')
            ->leftJoin('i.product', 'p')
            ->leftJoin('o.users', 'u')
            ->where('o.id=:id')
            ->setParameter('id', $id)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    public function findLastId()
    {
        return $this->createQueryBuilder('o')
            ->select([
                'o.id',
            ])
            ->orderBy('o.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

}
