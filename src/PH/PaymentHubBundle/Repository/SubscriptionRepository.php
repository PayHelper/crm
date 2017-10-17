<?php

/*
 * Copyright 2017 Sourcefabric z.ú. and contributors.
 */

namespace PH\PaymentHubBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PH\PaymentHubBundle\Entity\SubscriptionInterface;

/**
 * Class SubscriptionRepository.
 */
class SubscriptionRepository extends EntityRepository
{
    /**
     * Get completed subscriptions without activation notification send and with customer email set.
     *
     * @return \Doctrine\ORM\Query
     */
    public function getWithoutActivationNotification($maxResults = 100)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.activationEmailSend IS NULL')
            ->andWhere('s.state = :state')
            ->join('s.customer', 'c')
            ->andWhere('c.email IS NOT NULL')
            ->setParameter('state', SubscriptionInterface::STATE_COMPLETED)
            ->setMaxResults($maxResults)
            ->getQuery();

        return $qb;
    }
}
