<?php

namespace PH\PaymentHubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ph_subscription")
 * @Config
 */
class Subscription
{
    const STATE_CART = 'cart';
    const STATE_COMPLETED = 'completed';
    const STATE_PAYMENT_SELECTED = 'payment_selected';
    const STATE_PAYMENT_SKIPPED = 'payment_skipped';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @ConfigField
     * @var string
     */
    protected $checkoutState = OrderCheckoutInterface::STATE_CART;

    /**
     * @ORM\Column(type="string")
     * @ConfigField
     * @var string
     */
    protected $paymentState = PaymentInterface::STATE_NEW;

    /**
     * @ORM\Column(type="string")
     * @ConfigField
     *
     * @var string
     */
    protected $orderState = self::STATE_CART;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @ConfigField
     * @var \DateTime
     */
    protected $checkoutCompletedAt;

    /**
     * @ORM\Column(type="float")
     * @ConfigField
     * @var int
     */
    protected $number = 0;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @ConfigField
     * @var string
     */
    protected $notes;

    /**
     * @ORM\Column(type="float")
     * @ConfigField
     * @var float
     */
    protected $total = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCheckoutState()
    {
        return $this->checkoutState;
    }

    /**
     * @param string $checkoutState
     */
    public function setCheckoutState($checkoutState)
    {
        $this->checkoutState = $checkoutState;
    }

    /**
     * @return string
     */
    public function getPaymentState()
    {
        return $this->paymentState;
    }

    /**
     * @param string $paymentState
     */
    public function setPaymentState($paymentState)
    {
        $this->paymentState = $paymentState;
    }

    /**
     * @return string
     */
    public function getOrderState()
    {
        return $this->orderState;
    }

    /**
     * @param string $orderState
     */
    public function setOrderState($orderState)
    {
        $this->orderState = $orderState;
    }

    /**
     * @return \DateTime
     */
    public function getCheckoutCompletedAt()
    {
        return $this->checkoutCompletedAt;
    }

    /**
     * @param \DateTime $checkoutCompletedAt
     */
    public function setCheckoutCompletedAt($checkoutCompletedAt)
    {
        $this->checkoutCompletedAt = $checkoutCompletedAt;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param float $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }
}