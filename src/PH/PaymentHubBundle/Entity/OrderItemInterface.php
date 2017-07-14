<?php

namespace PH\PaymentHubBundle\Entity;

/**
 * Interface OrderItemInterface.
 */
interface OrderItemInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getQuantity();

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity);

    /**
     * @return float
     */
    public function getUnitPrice();

    /**
     * @param float $unitPrice
     */
    public function setUnitPrice($unitPrice);

    /**
     * @return float
     */
    public function getTotal();

    /**
     * @param float $total
     */
    public function setTotal($total);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return mixed
     */
    public function getSubscription();

    /**
     * @param mixed $subscription
     */
    public function setSubscription($subscription);

    /**
     * @return int
     */
    public function getOrderItemId();

    /**
     * @param int $orderItemId
     */
    public function setOrderItemId($orderItemId);

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
}
