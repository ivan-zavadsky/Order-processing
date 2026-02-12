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

        /**
         * Ищет продукты по названию без учета регистра
         *
         * @param string $query Текст для поиска
         * @return array Массив продуктов
         */
        public function findHint(string $query): array
        {
            return $this->createQueryBuilder('p')
                ->where('LOWER(p.name) LIKE LOWER(:query)')
                ->setParameter('query', '%' . $query . '%')
                ->orderBy('p.name', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();
        }

}
