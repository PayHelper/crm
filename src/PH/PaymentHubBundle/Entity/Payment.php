<?php

namespace PH\PaymentHubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Payment.
 *
 * @ORM\Entity()
 * @ORM\Table(name="ph_payment")
 */
class Payment implements PaymentInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $paymentId;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $methodCode;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $currencyCode;

    /**
     * @ORM\Column(type="float")
     *
     * @var string
     */
    protected $amount;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $state;

    /**
     * Many Features have One Product.
     *
     * @ORM\ManyToOne(targetEntity="PH\PaymentHubBundle\Entity\Subscription", inversedBy="payments")
     * @ORM\JoinColumn(name="subscription_id", referencedColumnName="id")
     */
    protected $subscription;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * @param int $paymentId
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodCode()
    {
        return $this->methodCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setMethodCode($methodCode)
    {
        $this->methodCode = $methodCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount()
    {
        if ($this->amount > 0) {
            return $this->amount / 100;
        }

        return $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * {@inheritdoc}
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * {@inheritdoc}
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}
