<?php

/*
 * Copyright 2017 Sourcefabric z.ú. and contributors.
 */

namespace PH\PaymentHubBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class CustomerRepository.
 */
class CustomerRepository extends EntityRepository
{
    /**
     * Get customers for email activation email.
     *
     * @param int $maxResults
     *
     * @return \Doctrine\ORM\Query
     */
    public function getForEmailVerification($maxResults = 100)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.processEmailVerification = :processEmailVerification')
            ->andWhere('c.email IS NOT NULL')
            ->setParameter('processEmailVerification', true)
            ->setMaxResults($maxResults)
            ->getQuery();

        return $qb;
    }
}
