<?php

namespace App\Repository;

use App\Entity\PageTypeContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PageTypeContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageTypeContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageTypeContact[]    findAll()
 * @method PageTypeContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageTypeContactRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageTypeContact::class);
    }

    public function getByPageId($pageId)
    {
        return $this->findOneBy(['page' => $pageId]);
    }
}
