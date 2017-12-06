<?php

namespace PH\PaymentHubBundle\Entity;

use Guzzle\Common\Collection;
use Oro\Bundle\AddressBundle\Entity\AbstractAddress;
use Oro\Bundle\BusinessEntitiesBundle\Entity\BasePerson;

interface CustomerInterface
{
    const CUSTOMER_UPDATED = 'payment_hub.customer.updated';
    const CUSTOMER_CREATED = 'payment_hub.customer.created';

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
     * @return string
     */
    public function getEmail();

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

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Get contact last update date/time.
     *
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * @param \DateTime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt);

    /**
     * @return mixed
     */
    public function getCustomerUpdateToken();

    /**
     * @param mixed $customerUpdateToken
     */
    public function setCustomerUpdateToken($customerUpdateToken);

    /**
     * @return mixed
     */
    public function getEmailVerificationToken();

    /**
     * @param mixed $emailVerificationToken
     */
    public function setEmailVerificationToken($emailVerificationToken);

    /**
     * @return mixed
     */
    public function getProcessEmailVerification();

    /**
     * @param mixed $processEmailVerification
     */
    public function setProcessEmailVerification($processEmailVerification);

    /**
     * @return mixed
     */
    public function getEmailVerifiedAt();

    /**
     * @param mixed $emailVerifiedAt
     */
    public function setEmailVerifiedAt($emailVerifiedAt);

    /**
     * @return mixed
     */
    public function getOwner();

    /**
     * @param mixed $owner
     */
    public function setOwner($owner);

    /**
     * @return mixed
     */
    public function getOrganization();

    /**
     * @param mixed $organization
     */
    public function setOrganization($organization);

    /**
     * @return mixed
     */
    public function getContactForbidden();

    /**
     * @param mixed $contactForbidden
     */
    public function setContactForbidden($contactForbidden);
}
