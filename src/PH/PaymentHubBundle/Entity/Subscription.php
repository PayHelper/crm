<?php

namespace PH\PaymentHubBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ph_subscription")
 * @Config
 */
class Subscription implements SubscriptionInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "identity"=true
     *          }
     *      }
     * )
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var int
     */
    protected $orderId;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $providerType;

    /**
     * @ORM\Column(type="string")
     * @ConfigField
     *
     * @var string
     */
    protected $checkoutState = OrderCheckoutInterface::STATE_CART;

    /**
     * @ORM\Column(type="string")
     * @ConfigField
     *
     * @var string
     */
    protected $paymentState = PaymentInterface::STATE_CART;

    /**
     * @ORM\Column(type="string")
     * @ConfigField
     *
     * @var string
     */
    protected $orderState = SubscriptionInterface::STATE_CART;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @ConfigField
     *
     * @var \DateTime
     */
    protected $checkoutCompletedAt;

    /**
     * @ORM\Column(type="float")
     * @ConfigField
     *
     * @var int
     */
    protected $number = 0;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $token = 0;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @ConfigField
     *
     * @var string
     */
    protected $notes;

    /**
     * @ORM\Column(type="float")
     * @ConfigField
     *
     * @var float
     */
    protected $total = 0;

    /**
     * @ORM\Column(type="datetime")
     * @Timestampable(on="create")
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Timestampable(on="update")
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="PH\PaymentHubBundle\Entity\OrderItem", mappedBy="subscription", cascade={"remove", "persist"})
     *
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "full"=true
     *          }
     *      }
     * )
     */
    protected $items;

    /**
     * @ORM\OneToMany(targetEntity="PH\PaymentHubBundle\Entity\Payment", mappedBy="subscription", cascade={"remove", "persist"})
     *
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "full"=true
     *          }
     *      }
     * )
     */
    protected $payments;

    /**
     * Many Features have One Product.
     *
     * @ORM\ManyToOne(targetEntity="PH\PaymentHubBundle\Entity\Customer", inversedBy="subscriptions", fetch="EAGER")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $interval;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @var |Date
     */
    protected $startDate;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $type;

    /**
     * Subscription constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getCheckoutState()
    {
        return $this->checkoutState;
    }

    /**
     * {@inheritdoc}
     */
    public function setCheckoutState($checkoutState)
    {
        $this->checkoutState = $checkoutState;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentState()
    {
        return $this->paymentState;
    }

    /**
     * {@inheritdoc}
     */
    public function setPaymentState($paymentState)
    {
        $this->paymentState = $paymentState;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderState()
    {
        return $this->orderState;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrderState($orderState)
    {
        $this->orderState = $orderState;
    }

    /**
     * {@inheritdoc}
     */
    public function getCheckoutCompletedAt()
    {
        return $this->checkoutCompletedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setCheckoutCompletedAt($checkoutCompletedAt)
    {
        $this->checkoutCompletedAt = $checkoutCompletedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * {@inheritdoc}
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * {@inheritdoc}
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * {@inheritdoc}
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * {@inheritdoc}
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
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

    /**
     * {@inheritdoc}
     */
    public function getProviderType()
    {
        return $this->providerType;
    }

    /**
     * {@inheritdoc}
     */
    public function setProviderType($providerType)
    {
        $this->providerType = $providerType;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * {@inheritdoc}
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * {@inheritdoc}
     */
    public function setPayments($payments)
    {
        $this->payments = $payments;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * {@inheritdoc}
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
