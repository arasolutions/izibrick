<?php

namespace App\Repository;

use App\Entity\PageType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PageType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageType[]    findAll()
 * @method PageType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageTypeRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageType::class);
    }
}
