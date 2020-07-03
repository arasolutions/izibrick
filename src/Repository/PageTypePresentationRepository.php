<?php

namespace App\Repository;

use App\Entity\PageTypePresentation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PageTypePresentation|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageTypePresentation|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageTypePresentation[]    findAll()
 * @method PageTypePresentation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageTypePresentationRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageTypePresentation::class);
    }

    public function getByPageId($pageId)
    {
        return $this->findOneBy(['page' => $pageId]);
    }
}
