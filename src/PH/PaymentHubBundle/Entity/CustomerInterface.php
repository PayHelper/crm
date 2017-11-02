<?php

namespace PH\PaymentHubBundle\Entity;

use Guzzle\Common\Collection;
use Oro\Bundle\AddressBundle\Entity\AbstractAddress;
use Oro\Bundle\BusinessEntitiesBundle\Entity\BasePerson;

interface CustomerInterface
{
    const CUSTOMER_UPDATED = 'payment_hub.customer.updated';

    /**
     * Set addresses.
     *
     * This method could not be named setAddresses because of bug CRM-253.
     *
     * @param Collection|AbstractAddress[] $addresses
     *
     * @return BasePerson
     */
    public function resetAddresses($addresses);

    /**
     * Add address.
     *
     * @param AbstractAddress $address
     *
     * @return BasePerson
     */
    public function addAddress(AbstractAddress $address);

    /**
     * Remove address.
     *
     * @param AbstractAddress $address
     *
     * @return BasePerson
     */
    public function removeAddress(AbstractAddress $address);

    /**
     * Get addresses.
     *
     * @return Collection|AbstractAddress[]
     */
    public function getAddresses();

    /**
     * @param AbstractAddress $address
     *
     * @return bool
     */
    public function hasAddress(AbstractAddress $address);

    /**
     * @return mixed
     */
    public function getSubscriptions();

    /**
     * @param mixed $subscriptions
     */
    public function setSubscriptions($subscriptions);

    /**
     * @param SubscriptionInterface $subscription
     */
    public function addSubscription(SubscriptionInterface $subscription);

    /**
     * @return mixed
     */
    public function getNewsletterAllowed();

    /**
     * @param mixed $newsletterAllowed
     */
    public function setNewsletterAllowed($newsletterAllowed);

    /**
     * @return mixed
     */
    public function getGiftAllowed();

    /**
     * @param mixed $giftAllowed
     */
    public function setGiftAllowed($giftAllowed);

    /**
     * @return mixed
     */
    public function getPhone();

    /**
     * @param mixed $phone
     */
    public function setPhone($phone);

    /**
     * @return mixed
     */
    public function getPublicComment();

    /**
     * @param mixed $publicComment
     */
    public function setPublicComment($publicComment);

    /**
     * @return mixed
     */
    public function getComment();

    /**
     * @param mixed $comment
     */
    public function setComment($comment);
}
