<?php

namespace App\Repository;

use App\Entity\TrackingContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TrackingContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrackingContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrackingContact[]    findAll()
 * @method TrackingContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackingContactRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrackingContact::class);
    }
}
