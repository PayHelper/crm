<?php

/*
 * Copyright 2017 Sourcefabric z.ú. and contributors.
 */

namespace PH\PaymentHubBundle\Entity;

interface NotificationLogInterface
{
    const TYPE_SUBSCRIPTION_ACTIVATION = 'activation';
    const TYPE_CUSTOMER_EMAIL_VERIFICATION = 'customer_email_verification';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getCustomer();

    /**
     * @param mixed $customer
     */
    public function setCustomer($customer);

    /**
     * @return mixed
     */
    public function getSubscription();

    /**
     * @param mixed $subscription
     */
    public function setSubscription($subscription);

    /**
     * @return mixed
     */
    public function getType();

    /**
     * @param mixed $type
     */
    public function setType($type);

    /**
     * @return mixed
     */
    public function getEmailContent();

    /**
     * @param mixed $emailContent
     */
    public function setEmailContent($emailContent);

    /**
     * @return mixed
     */
    public function getSendAt();

    /**
     * @param mixed $sendAt
     */
    public function setSendAt($sendAt);
}
