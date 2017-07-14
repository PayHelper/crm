<?php

namespace PH\PaymentHubBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\BusinessEntitiesBundle\Entity\BasePerson;
use Oro\Bundle\ChannelBundle\Model\ChannelAwareInterface;
use Oro\Bundle\ChannelBundle\Model\ChannelEntityTrait;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * Class Customer.
 *
 * @ORM\Entity()
 * @ORM\Table(name="ph_customer")
 * @Config
 */
class Customer extends BasePerson implements ChannelAwareInterface
{
    use ChannelEntityTrait;

    /**
     * @ORM\OneToMany(targetEntity="PH\PaymentHubBundle\Entity\Subscription", mappedBy="customer")
     */
    protected $subscriptions;

    /**
     * Customer constructor.
     */
    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();

        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param mixed $subscriptions
     */
    public function setSubscriptions($subscriptions)
    {
        $this->subscriptions = $subscriptions;
    }

    /**
     * @param SubscriptionInterface $subscription
     */
    public function addSubscription(SubscriptionInterface $subscription)
    {
        $this->subscriptions->add($subscription);
    }
}
