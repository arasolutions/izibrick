<?php

namespace App\Repository;

use App\Entity\PageTypeHome;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PageTypeHome|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageTypeHome|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageTypeHome[]    findAll()
 * @method PageTypeHome[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageTypeHomeRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageTypeHome::class);
    }
}
