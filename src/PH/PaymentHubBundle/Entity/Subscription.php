<?php

/*
 * Copyright 2017 Sourcefabric z.ú. and contributors.
 */

namespace PH\PaymentHubBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\OrganizationBundle\Entity\Organization;

/**
 * @ORM\Entity(repositoryClass="PH\PaymentHubBundle\Repository\SubscriptionRepository")
 * @ORM\Table(name="ph_subscription")
 *
 * @Config(
 *      defaultValues={
 *          "ownership"={
 *              "owner_type"="BUSINESS_UNIT",
 *              "owner_field_name"="owner",
 *              "owner_column_name"="business_unit_owner_id",
 *              "organization_field_name"="organization",
 *              "organization_column_name"="organization_id"
 *          },
 *          "security"={
 *              "type"="ACL",
 *              "group_name"="",
 *              "category"="subscription_management",
 *              "field_acl_supported"="true"
 *          },
 *     }
 * )
 */
class Subscription implements SubscriptionInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @ConfigField(
     *     defaultValues={
     *         "importexport"={
     *             "identity"=true
     *         }
     *     }
     * )
     *
     * @var int
     */
    private $id;

    /**
     * @var \Oro\Bundle\OrganizationBundle\Entity\BusinessUnit
     *
     * @ConfigField(
     *     defaultValues={
     *         "importexport"={
     *             "full"=false
     *         }
     *     }
     * )
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\BusinessUnit", cascade={"persist"})
     * @ORM\JoinColumn(name="business_unit_owner_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $owner;

    /**
     * @var Organization
     *
     * @ConfigField(
     *     defaultValues={
     *         "importexport"={
     *             "full"=false
     *         }
     *     }
     * )
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $organization;

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
     *
     * @var string
     */
    protected $purchaseState = OrderCheckoutInterface::STATE_NEW;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $paymentState = PaymentInterface::STATE_NEW;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $state = SubscriptionInterface::STATE_NEW;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $checkoutCompletedAt;

    /**
     * @ORM\Column(type="float")
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
     *
     * @var string
     */
    protected $notes;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    protected $total = 0;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Timestampable(on="create")
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Timestampable(on="update")
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="PH\PaymentHubBundle\Entity\OrderItem", mappedBy="subscription", cascade={"remove", "persist"})
     *
     * @ConfigField(
     *     defaultValues={
     *         "importexport"={
     *             "full"=false
     *         }
     *     }
     * )
     */
    protected $items;

    /**
     * @ORM\OneToMany(targetEntity="PH\PaymentHubBundle\Entity\Payment", mappedBy="subscription", cascade={"remove", "persist"})
     * @ORM\OrderBy({"paymentId" = "DESC"})
     *
     * @ConfigField(
     *     defaultValues={
     *         "importexport"={
     *             "full"=false
     *         }
     *     }
     * )
     */
    protected $payments;

    /**
     * @ORM\OneToMany(targetEntity="PH\PaymentHubBundle\Entity\NotificationLog", mappedBy="subscription", cascade={"remove", "persist"})
     *
     * @ConfigField(
     *     defaultValues={
     *         "importexport"={
     *             "excluded"=true
     *         }
     *     }
     * )
     */
    protected $notifications;

    /**
     * @ORM\ManyToOne(targetEntity="PH\PaymentHubBundle\Entity\Customer", inversedBy="subscriptions", fetch="EAGER")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     *
     * @ConfigField(
     *     defaultValues={
     *         "importexport"={
     *             "full"=false
     *         }
     *     }
     * )
     */
    protected $customer;

    /**
     * @ORM\Column(type="string", nullable=true, name="`interval`")
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
     * @ORM\Column(type="string", name="`type`")
     *
     * @var string
     */
    protected $type;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $activationEmailSend;

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
    public function getPurchaseState()
    {
        return $this->purchaseState;
    }

    /**
     * {@inheritdoc}
     */
    public function setPurchaseState($purchaseState)
    {
        $this->purchaseState = $purchaseState;
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
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * {@inheritdoc}
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
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
     * {@inheritdoc}
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * {@inheritdoc}
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getActivationEmailSend()
    {
        return $this->activationEmailSend;
    }

    /**
     * {@inheritdoc}
     */
    public function setActivationEmailSend($activationEmailSend)
    {
        $this->activationEmailSend = $activationEmailSend;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     *{@inheritdoc}
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }
}
