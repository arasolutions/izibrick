<?php

namespace App\Repository;

use App\Entity\PricingProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PricingProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method PricingProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method PricingProduct[]    findAll()
 * @method PricingProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PricingProductRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PricingProduct::class);
    }
}
