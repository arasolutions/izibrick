<?php

namespace App\Repository;

use App\Entity\PricingCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PricingCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PricingCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PricingCategory[]    findAll()
 * @method PricingCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PricingCategoryRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PricingCategory::class);
    }
}
