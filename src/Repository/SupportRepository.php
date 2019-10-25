<?php

namespace App\Repository;

use App\Entity\Support;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Support|null find($id, $lockMode = null, $lockVersion = null)
 * @method Support|null findOneBy(array $criteria, array $orderBy = null)
 * @method Support[]    findAll()
 * @method Support[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupportRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Support::class);
    }
}
