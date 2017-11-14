<?php

/*
 * Copyright 2017 Sourcefabric z.ú. and contributors.
 */

namespace PH\PaymentHubBundle\Service;

use PH\PaymentHubBundle\Entity\CustomerInterface;

/**
 * Interface SubscriptionServiceInterface.
 */
interface CustomerServiceInterface
{
    /**
     * @param CustomerInterface $customer
     *
     * @return mixed
     */
    public function prepareCustomer(CustomerInterface $customer);

    /**
     * @param CustomerInterface $customer
     *
     * @return mixed
     */
    public function reassignAddresses(CustomerInterface $customer);

    /**
     * @param CustomerInterface $customer
     *
     * @return mixed
     */
    public function resetUpdateToken(CustomerInterface $customer);
}
