<?php

namespace PH\PaymentHubBundle\Entity;

/**
 * Interface Subscription.
 */
interface SubscriptionInterface
{
    const STATE_NEW = 'new';
    const STATE_CANCELED = 'cancelled';
    const STATE_REFUNDED = 'refunded';
    const STATE_FULFILLED = 'fulfilled';
    const STATE_EXPIRED = 'expired';

    const INTERVAL_MONTH = '1 month';
    const INTERVAL_YEAR = '1 year';
    const INTERVAL_QUARTERLY = '3 months';

    const TYPE_RECURRING = 'recurring';
    const TYPE_NONRECURRING = 'non-recurring';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getPaymentState();

    /**
     * @param string $paymentState
     */
    public function setPaymentState($paymentState);

    /**
     * @return string
     */
    public function getPurchaseState();

    /**
     * @param string $purchaseState
     */
    public function setPurchaseState($purchaseState);

    /**
     * @return string
     */
    public function getState();

    /**
     * @param string $state
     */
    public function setState($state);

    /**
     * @return \DateTime
     */
    public function getCheckoutCompletedAt();

    /**
     * @param \DateTime $checkoutCompletedAt
     */
    public function setCheckoutCompletedAt($checkoutCompletedAt);

    /**
     * @return int
     */
    public function getNumber();

    /**
     * @param int $number
     */
    public function setNumber($number);

    /**
     * @return string
     */
    public function getNotes();

    /**
     * @param string $notes
     */
    public function setNotes($notes);

    /**
     * @return float
     */
    public function getTotal();

    /**
     * @param float $total
     */
    public function setTotal($total);

    /**
     * @return mixed
     */
    public function getOrderId();

    /**
     * @param mixed $orderId
     */
    public function setOrderId($orderId);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt);

    /**
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt);

    /**
     * @return string
     */
    public function getProviderType();

    /**
     * @param string $providerType
     */
    public function setProviderType($providerType);

    /**
     * @return mixed
     */
    public function getItems();

    /**
     * @param mixed $items
     */
    public function setItems($items);

    /**
     * @return mixed
     */
    public function getPayments();

    /**
     * @param mixed $payments
     */
    public function setPayments($payments);

    /**
     * @return mixed
     */
    public function getCustomer();

    /**
     * @param mixed $customer
     */
    public function setCustomer($customer);

    /**
     * @return string
     */
    public function getInterval();

    /**
     * @param string $interval
     */
    public function setInterval($interval);

    /**
     * @return string
     */
    public function getToken();

    /**
     * @param string $token
     */
    public function setToken($token);

    /**
     * @return mixed
     */
    public function getStartDate();

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate);

    /**
     * @return mixed
     */
    public function getType();

    /**
     * @param mixed $type
     */
    public function setType($type);

    /**
     * @return \DateTime
     */
    public function getActivationEmailSend();

    /**
     * @param \DateTime $activationEmailSend
     */
    public function setActivationEmailSend($activationEmailSend);

    /**
     * @return mixed
     */
    public function getNotifications();

    /**
     * @param mixed $notifications
     */
    public function setNotifications($notifications);

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
     * @return string
     */
    public function getIntention();

    /**
     * @param string $intention
     */
    public function setIntention($intention);

    /**
     * @return string
     */
    public function getSource();

    /**
     * @param string $source
     */
    public function setSource($source);

    /**
     * @return \DateTimeInterface
     */
    public function getEndDate();

    /**
     * @param \DateTimeInterface $endDate
     */
    public function setEndDate(\DateTimeInterface $endDate);
}
