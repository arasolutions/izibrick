<?php

namespace App\Repository;

use App\Entity\PageTypeBlog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PageTypeBlog|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageTypeBlog|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageTypeBlog[]    findAll()
 * @method PageTypeBlog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageTypeBlogRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageTypeBlog::class);
    }

    public function getByPageId($pageId)
    {
        return $this->findOneBy(['page' => $pageId]);
    }
}
