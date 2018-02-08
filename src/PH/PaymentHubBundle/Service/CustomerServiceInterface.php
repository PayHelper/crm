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
    const EMAIL_VERIFICATION_TEMPLATE = 'customer_email_verification';

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

    /**
     * @param CustomerInterface $customer
     **/
    public function setIdentificationToken(CustomerInterface $customer);

    /**
     * @param CustomerInterface $customer
     *
     * @return array
     */
    public function sendEmailVerificationEmail(CustomerInterface $customer);
}
