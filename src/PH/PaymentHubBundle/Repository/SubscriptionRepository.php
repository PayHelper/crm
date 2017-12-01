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
            ->setParameter('state', SubscriptionInterface::STATE_FULFILLED)
            ->setMaxResults($maxResults)
            ->getQuery();

        return $qb;
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getEndedSubscriptions()
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.endDate IS NOT NULL')
            ->andWhere('s.state IN (:states)')
            ->andWhere('s.endDate <= :currentDate')
            ->setParameters([
                'states' => [
                    SubscriptionInterface::STATE_FULFILLED,
                    SubscriptionInterface::STATE_NEW,
                ],
                'currentDate' => new \DateTime(),
            ])
            ->getQuery();

        return $qb;
    }
}
