<?php

namespace App\Repository;

use App\Entity\TrackingQuote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TrackingQuote|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrackingQuote|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrackingQuote[]    findAll()
 * @method TrackingQuote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackingQuoteRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrackingQuote::class);
    }
}
