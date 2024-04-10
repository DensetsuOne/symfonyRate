<?php

namespace App\Repository;

use App\Entity\Rate;
use Doctrine\ORM\EntityRepository;

/**
 * @method Rate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rate[] findAll()
 * @method Rate[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RateRepository extends EntityRepository
{

    public function findByCode(string $code)
    {
        $qb = $this->createQueryBuilder('rate');
        $qb
            ->where('rate.charCode = :code')
            ->setParameter('code', $code)
        ;
        return $qb;
    }
}
