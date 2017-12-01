<?php

namespace PH\PaymentHubBundle\Service;

use PH\PaymentHubBundle\Entity\SubscriptionInterface;

interface SubscriptionCancellerInterface
{
    public function cancel(SubscriptionInterface $subscription);
}
