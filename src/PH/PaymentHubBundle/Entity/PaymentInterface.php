<?php

namespace PH\PaymentHubBundle\Entity;

/**
 * Interface PaymentInterface.
 */
interface PaymentInterface
{
    const STATE_NEW = 'new';
    const STATE_AWAITING_PAYMENT = 'awaiting_payment';
    const STATE_PARTIALLY_PAID = 'partially_paid';
    const STATE_CANCELLED = 'cancelled';
    const STATE_PAID = 'paid';
    const STATE_PARTIALLY_REFUNDED = 'partially_refunded';
    const STATE_REFUNDED = 'refunded';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getPaymentId();

    /**
     * @param int $paymentId
     */
    public function setPaymentId($paymentId);

    /**
     * @return string
     */
    public function getMethodCode();

    /**
     * @param string $methodCode
     */
    public function setMethodCode($methodCode);

    /**
     * @return string
     */
    public function getCurrencyCode();

    /**
     * @param string $currencyCode
     */
    public function setCurrencyCode($currencyCode);

    /**
     * @return string
     */
    public function getAmount();

    /**
     * @param string $amount
     */
    public function setAmount($amount);

    /**
     * @return string
     */
    public function getState();

    /**
     * @param string $state
     */
    public function setState($state);

    /**
     * @return mixed
     */
    public function getSubscription();

    /**
     * @param mixed $subscription
     */
    public function setSubscription($subscription);

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param \DateTime $createdAt
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
}
