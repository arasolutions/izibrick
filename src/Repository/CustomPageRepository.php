<?php

namespace App\Repository;

use App\Entity\CustomPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CustomPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomPage[]    findAll()
 * @method CustomPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomPageRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomPage::class);
    }

    /**
     * @param $id
     * @return CustomPage
     */
    public function getBySiteAndId($site, $id)
    {
        try {
            return $this->createQueryBuilder('c')
                ->andWhere('c.id = :id')
                ->andWhere('c.site = :site')
                ->setParameter('id', $id)
                ->setParameter('site', $site)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
    /**
     * @param $id
     * @return CustomPage
     */
    public function getBySiteAndNameUrl($site, $nameUrl)
    {
        try {
            return $this->createQueryBuilder('c')
                ->andWhere('c.nameMenuUrl = :nameUrl')
                ->andWhere('c.site = :site')
                ->setParameter('nameUrl', $nameUrl)
                ->setParameter('site', $site)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NoResultException $e) {
            return null;
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
}
