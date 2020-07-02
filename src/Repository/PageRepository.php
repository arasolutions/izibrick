<?php

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    /**
     * @param $site
     * @param $id
     * @return CustomPage
     */
    public function getBySiteAndId($site, $id)
    {
        try {
            return $this->createQueryBuilder('p')
                ->andWhere('p.id = :id')
                ->andWhere('p.site = :site')
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
     * @return Page
     */
    public function getBySiteAndNameUrl($site, $nameUrl)
    {
        try {
            return $this->createQueryBuilder('p')
                ->andWhere('p.nameMenuUrl = :nameUrl')
                ->andWhere('p.site = :site')
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

    public function getAlBySiteId($siteId)
    {
        return $this->findBy(['site' => $siteId]);
    }
}
