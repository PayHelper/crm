<?php

/*
 * Copyright 2017 Sourcefabric z.ú. and contributors.
 */

namespace PH\PaymentHubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * Class OrderItem.
 *
 * @ORM\Entity()
 * @ORM\Table(name="ph_notification_log")
 * @Config
 */
class NotificationLog implements NotificationLogInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
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
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="PH\PaymentHubBundle\Entity\Customer", inversedBy="subscriptions", fetch="EAGER")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\ManyToOne(targetEntity="PH\PaymentHubBundle\Entity\Subscription", inversedBy="payments", cascade={"persist"})
     * @ORM\JoinColumn(name="subscription_id", referencedColumnName="id")
     */
    protected $subscription;

    /**
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $emailContent;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $sendAt;

    public function __construct($type)
    {
        $this->setType($type);
        $this->setSendAt(new \DateTime('now'));
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
    public function getEmailContent()
    {
        return $this->emailContent;
    }

    /**
     * {@inheritdoc}
     */
    public function setEmailContent($emailContent)
    {
        $this->emailContent = $emailContent;
    }

    /**
     * {@inheritdoc}
     */
    public function getSendAt()
    {
        return $this->sendAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setSendAt($sendAt)
    {
        $this->sendAt = $sendAt;
    }
}
