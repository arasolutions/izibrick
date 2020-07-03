<?php

namespace App\Repository;

use App\Entity\Link;
use App\Entity\PricingProduct;
use App\Entity\Product;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Enum\SiteStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr;

/**
 * @method Site|null find($id, $lockMode = null, $lockVersion = null)
 * @method Site|null findOneBy(array $criteria, array $orderBy = null)
 * @method Site[]    findAll()
 * @method Site[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Site::class);
    }

    /**
     * @param Site $site
     * @return Site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Site $site)
    {
        $this->getEntityManager()->persist($site);
        $this->getEntityManager()->flush();
        return $site;
    }

    /**
     * @param $id
     * @return Site
     */
    public function getById($id)
    {
        try {
            return $this->createQueryBuilder('s')
                ->andWhere('s.id = :val')
                ->setParameter('val', $id)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * @param $internalName
     * @return mixed|null
     */
    public function getByInternalName($internalName)
    {
        try {
            $q = $this->createQueryBuilder('s')
                ->andWhere('LOWER(s.internalName) = :val')
                ->setParameter('val', strtolower($internalName))
                ->getQuery();

            //var_dump($q->getSQL());die();

            return $q->getSingleResult();
            //var_dump($site);die();
        } catch (NoResultException $e) {
            return null;
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * @param $domain
     * @return mixed|null
     */
    public function getByDomain($domain)
    {
        try {
            $q = $this->createQueryBuilder('s')
                ->andWhere('s.domain = :val')
                ->setParameter('val', 'https://' . $domain)
                ->getQuery();
            
            return $q->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function findAllActiveSiteByUser(User $user)
    {
        $q = $this->createQueryBuilder('s')
            ->join(UserSite::class, 'us', Expr\Join::WITH, 'us.site = s')
            ->andWhere('us.user = :user')
            ->andWhere('us.active = 1')
            ->andWhere('s.status in (:status)')
            ->setParameter('user', $user)
            ->setParameter('status', array(SiteStatus::ACTIF['name'], SiteStatus::INITIALISE['name']))
            ->getQuery();

        return $q->getResult();
    }

}
