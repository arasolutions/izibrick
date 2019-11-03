<?php

namespace App\Repository;

use App\Entity\CodePromotion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CodePromotion|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodePromotion|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodePromotion[]    findAll()
 * @method CodePromotion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodePromotionRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodePromotion::class);
    }


    /**
     * @param $siteName
     * @return mixed|null
     */
    public function getByName($codePromotion, $product)
    {
        try {
            $q = $this->createQueryBuilder('c')
                ->andWhere('LOWER(c.name) = :val')
                ->andWhere('c.product = :product')
                ->setParameter('val', strtolower($codePromotion))
                ->setParameter('product', $product)
                ->getQuery();
            return $q->getOneOrNullResult();
        } catch (NoResultException $e) {
            return null;
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

}
